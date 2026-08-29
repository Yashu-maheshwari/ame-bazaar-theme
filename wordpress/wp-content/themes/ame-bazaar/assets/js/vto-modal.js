/**
 * AME Bazaar Virtual Try-On (VTO) Modal & Fitting Engine.
 * Manages customer portrait uploads, camera capture, asynchronous IDM-VTON
 * job streaming, split comparison slider, and high-definition result viewing.
 */
(function ($) {
  'use strict';

  var VTO = {
    modalRoot: null,
    personImage: null,
    garmentImage: null,
    selectedCategory: 'upper_body',
    sliderPos: 50,
    isDraggingSlider: false,
    activeAbortController: null,

    init: function () {
      this.modalRoot = document.getElementById('ame-vto-modal-root');
      if (!this.modalRoot) return;

      // Default garment from localized data or product page
      if (typeof ameBazaarVTO !== 'undefined' && ameBazaarVTO.defaultGarment) {
        this.garmentImage = ameBazaarVTO.defaultGarment;
      }
      if (typeof ameBazaarVTO !== 'undefined' && ameBazaarVTO.detectedCategory) {
        this.selectedCategory = ameBazaarVTO.detectedCategory;
      }

      this.bindEvents();
    },

    bindEvents: function () {
      var self = this;

      // Open Modal button
      $(document).on('click', '#ame-open-vto-btn', function (e) {
        e.preventDefault();
        self.openModal($(this));
      });

      // Close Modal buttons & backdrop click
      $(document).on('click', '#ame-vto-close-btn, #ame-vto-continue-shopping-btn', function (e) {
        e.preventDefault();
        self.closeModal();
      });

      $(document).on('click', '#ame-vto-modal-root', function (e) {
        if (e.target === this) {
          self.closeModal();
        }
      });

      // Escape key to close
      $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $(self.modalRoot).hasClass('active')) {
          self.closeModal();
        }
      });

      // Category Selection buttons
      $(document).on('click', '.ame-vto-cat-btn', function () {
        $('.ame-vto-cat-btn').removeClass('active');
        $(this).addClass('active');
        self.selectedCategory = $(this).data('category') || 'upper_body';
      });

      // Dropzone click & file change
      $(document).on('click', '#ame-vto-empty-upload-view', function (e) {
        if (e.target.closest('#ame-vto-camera-btn')) return;
        $('#ame-vto-file-input').trigger('click');
      });

      $(document).on('change', '#ame-vto-file-input', function (e) {
        if (this.files && this.files[0]) {
          self.processImageFile(this.files[0]);
        }
      });

      // Drag & Drop onto person dropzone
      var dropzone = document.getElementById('ame-vto-person-dropzone');
      if (dropzone) {
        dropzone.addEventListener('dragover', function (e) {
          e.preventDefault();
          e.stopPropagation();
          $(this).addClass('dragover');
        });

        dropzone.addEventListener('dragleave', function (e) {
          e.preventDefault();
          e.stopPropagation();
          $(this).removeClass('dragover');
        });

        dropzone.addEventListener('drop', function (e) {
          e.preventDefault();
          e.stopPropagation();
          $(dropzone).removeClass('dragover');
          if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            self.processImageFile(e.dataTransfer.files[0]);
          }
        });
      }

      // Camera Snap Trigger
      $(document).on('click', '#ame-vto-camera-btn', function (e) {
        e.stopPropagation();
        self.triggerCameraCapture();
      });

      // Remove Person photo
      $(document).on('click', '#ame-vto-remove-person-btn', function (e) {
        e.stopPropagation();
        self.personImage = null;
        $('#ame-vto-person-preview-wrap').hide();
        $('#ame-vto-empty-upload-view').show();
        $('#ame-vto-file-input').val('');
      });

      // Generate button
      $(document).on('click', '#ame-vto-generate-btn', function (e) {
        e.preventDefault();
        self.startFittingJob();
      });

      // Result View Toggles (Split / Side-by-Side / Full)
      $(document).on('click', '.ame-vto-toggle-btn', function () {
        var view = $(this).data('view');
        $('.ame-vto-toggle-btn').removeClass('active');
        $(this).addClass('active');

        if (view === 'split') {
          $('#ame-vto-comparison-viewport').show();
          $('#ame-vto-side-by-side-grid').hide();
          $('#ame-vto-single-viewport').hide();
        } else if (view === 'side') {
          $('#ame-vto-comparison-viewport').hide();
          $('#ame-vto-side-by-side-grid').show();
          $('#ame-vto-single-viewport').hide();
        } else if (view === 'single') {
          $('#ame-vto-comparison-viewport').hide();
          $('#ame-vto-side-by-side-grid').hide();
          $('#ame-vto-single-viewport').show();
        }
      });

      // Split Slider Dragging
      var sliderViewport = document.getElementById('ame-vto-comparison-viewport');
      if (sliderViewport) {
        var updateSlider = function (clientX) {
          var rect = sliderViewport.getBoundingClientRect();
          var offsetX = clientX - rect.left;
          var pct = Math.max(0, Math.min(100, (offsetX / rect.width) * 100));
          self.sliderPos = pct;
          $('#ame-vto-clip-layer').css('width', pct + '%');
          $('#ame-vto-slider-handle').css('left', pct + '%');
        };

        $(sliderViewport).on('mousedown touchstart', function (e) {
          self.isDraggingSlider = true;
          var clientX = e.type === 'touchstart' ? e.originalEvent.touches[0].clientX : e.clientX;
          updateSlider(clientX);
        });

        $(window).on('mousemove touchmove', function (e) {
          if (!self.isDraggingSlider) return;
          var clientX = e.type === 'touchmove' ? e.originalEvent.touches[0].clientX : e.clientX;
          updateSlider(clientX);
        });

        $(window).on('mouseup touchend', function () {
          self.isDraggingSlider = false;
        });
      }

      // Download HD button
      $(document).on('click', '#ame-vto-download-btn', function () {
        var resultUrl = $('#ame-vto-final-result-img').attr('src');
        if (!resultUrl) return;

        var a = document.createElement('a');
        a.href = resultUrl;
        a.download = 'ame-bazaar-fitted-look-' + Date.now() + '.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
      });

      // Try Another Photo
      $(document).on('click', '#ame-vto-try-another-btn', function () {
        self.showScreen('setup');
      });
    },

    openModal: function ($triggerBtn) {
      // Find the active main product image if available
      var dynamicGarmentUrl = $triggerBtn.data('garment-url');
      var $wcMainImg = $('.woocommerce-product-gallery__image img, .ame-product-gallery-column img').first();
      if ($wcMainImg.length && $wcMainImg.attr('src')) {
        dynamicGarmentUrl = $wcMainImg.attr('src');
      }

      if (dynamicGarmentUrl) {
        this.garmentImage = dynamicGarmentUrl;
      }

      var productTitle = (typeof ameBazaarVTO !== 'undefined' && ameBazaarVTO.productTitle) ? ameBazaarVTO.productTitle : '';
      if (!productTitle) {
        productTitle = $('h1.product_title, .ame-product-summary-column h1').first().text().trim() || 'Selected Garment';
      }

      $('#ame-vto-garment-img').attr('src', this.garmentImage);
      $('#ame-vto-garment-title').text(productTitle);

      // Select matching category button
      $('.ame-vto-cat-btn').removeClass('active');
      $('.ame-vto-cat-btn[data-category="' + this.selectedCategory + '"]').addClass('active');

      this.showScreen('setup');
      $('#ame-vto-error-banner').hide();
      $(this.modalRoot).addClass('active').attr('aria-hidden', 'false');
      $('body').addClass('ame-vto-modal-open');
    },

    closeModal: function () {
      if (this.activeAbortController) {
        this.activeAbortController.abort();
        this.activeAbortController = null;
      }
      $(this.modalRoot).removeClass('active').attr('aria-hidden', 'true');
      $('body').removeClass('ame-vto-modal-open');
    },

    showScreen: function (screenName) {
      $('.ame-vto-screen').hide().removeClass('active');
      if (screenName === 'setup') {
        $('#ame-vto-setup-screen').show().addClass('active');
      } else if (screenName === 'loading') {
        $('#ame-vto-loading-screen').show().addClass('active');
      } else if (screenName === 'result') {
        $('#ame-vto-result-screen').show().addClass('active');
      }
    },

    processImageFile: function (file) {
      var self = this;
      if (!file || !file.type.match(/image.*/)) {
        self.showError('Please upload a valid image file (JPG, PNG, or WebP).');
        return;
      }

      var reader = new FileReader();
      reader.onload = function (e) {
        var rawDataUrl = e.target.result;
        // Optimize & resize image on canvas before memory allocation
        self.optimizeImageCanvas(rawDataUrl, function (optimizedDataUrl) {
          self.personImage = optimizedDataUrl;
          $('#ame-vto-person-preview-img').attr('src', optimizedDataUrl);
          $('#ame-vto-empty-upload-view').hide();
          $('#ame-vto-person-preview-wrap').show();
          $('#ame-vto-error-banner').hide();
        });
      };
      reader.onerror = function () {
        self.showError('Could not read uploaded photo.');
      };
      reader.readAsDataURL(file);
    },

    optimizeImageCanvas: function (dataUrl, callback) {
      var img = new Image();
      img.onload = function () {
        var maxW = 768;
        var maxH = 1024;
        var w = img.width;
        var h = img.height;

        if (w > maxW || h > maxH) {
          var ratio = Math.min(maxW / w, maxH / h);
          w = Math.round(w * ratio);
          h = Math.round(h * ratio);
        }

        var canvas = document.createElement('canvas');
        canvas.width = w;
        canvas.height = h;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, w, h);
        var optimized = canvas.toDataURL('image/jpeg', 0.90);
        callback(optimized);
      };
      img.onerror = function () {
        callback(dataUrl);
      };
      img.src = dataUrl;
    },

    triggerCameraCapture: function () {
      var self = this;
      if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        $('#ame-vto-file-input').trigger('click');
        return;
      }

      // Open camera stream in a video element
      var video = document.createElement('video');
      video.setAttribute('playsinline', '');
      video.setAttribute('autoplay', '');

      navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
        .then(function (stream) {
          var overlay = $('<div class="ame-vto-camera-overlay"><div class="ame-vto-camera-box"><div class="ame-vto-camera-view"></div><div class="ame-vto-camera-controls"><button type="button" class="ame-vto-snap-btn">📸 Snap Photo</button><button type="button" class="ame-vto-cancel-cam-btn">Cancel</button></div></div></div>');
          $('body').append(overlay);
          overlay.find('.ame-vto-camera-view').append(video);
          video.srcObject = stream;
          video.play();

          overlay.find('.ame-vto-snap-btn').on('click', function () {
            var canvas = document.createElement('canvas');
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            var snapUrl = canvas.toDataURL('image/jpeg', 0.90);
            self.optimizeImageCanvas(snapUrl, function (optimized) {
              self.personImage = optimized;
              $('#ame-vto-person-preview-img').attr('src', optimized);
              $('#ame-vto-empty-upload-view').hide();
              $('#ame-vto-person-preview-wrap').show();
            });
            stream.getTracks().forEach(function (t) { t.stop(); });
            overlay.remove();
          });

          overlay.find('.ame-vto-cancel-cam-btn').on('click', function () {
            stream.getTracks().forEach(function (t) { t.stop(); });
            overlay.remove();
          });
        })
        .catch(function () {
          $('#ame-vto-file-input').trigger('click');
        });
    },

    showError: function (msg) {
      $('#ame-vto-error-text').text(msg || 'An error occurred. Please try again.');
      $('#ame-vto-error-banner').show();
    },

    dataUriToBlob: function (dataUri) {
      var byteString = atob(dataUri.split(',')[1]);
      var mimeString = dataUri.split(',')[0].split(':')[1].split(';')[0];
      var ab = new ArrayBuffer(byteString.length);
      var ia = new Uint8Array(ab);
      for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
      }
      return new Blob([ab], { type: mimeString });
    },

    urlToBlob: async function (url) {
      var res = await fetch(url, { mode: 'cors' });
      return await res.blob();
    },

    startFittingJob: async function () {
      var self = this;
      if (!self.personImage) {
        self.showError('Please upload or snap a photo of yourself first.');
        return;
      }
      if (!self.garmentImage) {
        self.showError('No garment product image found for fitting.');
        return;
      }

      $('#ame-vto-error-banner').hide();
      self.showScreen('loading');
      $('#ame-vto-progress-fill').css('width', '15%');
      $('#ame-vto-loading-stage').text('Encoding portrait and garment tensors...');

      self.activeAbortController = new AbortController();
      var signal = self.activeAbortController.signal;

      try {
        // Step 1: Prepare Blobs
        var personBlob = self.dataUriToBlob(self.personImage);
        var garmentBlob;
        if (self.garmentImage.startsWith('data:')) {
          garmentBlob = self.dataUriToBlob(self.garmentImage);
        } else {
          garmentBlob = await self.urlToBlob(self.garmentImage);
        }

        // Step 2: Upload files to Gradio space /upload
        $('#ame-vto-progress-fill').css('width', '30%');
        $('#ame-vto-loading-stage').text('Submitting to IDM-VTON neural cluster...');

        var uploadForm = new FormData();
        uploadForm.append('files', personBlob, 'person.jpg');
        uploadForm.append('files', garmentBlob, 'garment.jpg');

        var uploadRes = await fetch('https://yisol-idm-vton.hf.space/upload', {
          method: 'POST',
          body: uploadForm,
          signal: signal
        });

        if (!uploadRes.ok) {
          throw new Error('Server is busy, try again.');
        }

        var uploadedPaths = await uploadRes.json();
        var personPath = uploadedPaths[0];
        var garmentPath = uploadedPaths[1];

        // Step 3: Queue join
        $('#ame-vto-progress-fill').css('width', '45%');
        $('#ame-vto-loading-stage').text('Queueing neural try-on warp...');

        var sessionHash = Math.random().toString(36).substring(2);
        var promptDesc = 'Fit and warp this ' + self.selectedCategory.replace('_', ' ') + ' garment cleanly onto the human model torso and pose.';

        var joinPayload = {
          data: [
            { background: { path: personPath }, layers: [], composite: null },
            { path: garmentPath },
            promptDesc,
            true,
            false,
            20,
            42
          ],
          event_data: null,
          fn_index: 2,
          trigger_id: 25,
          session_hash: sessionHash
        };

        var joinRes = await fetch('https://yisol-idm-vton.hf.space/queue/join', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(joinPayload),
          signal: signal
        });

        if (!joinRes.ok) {
          throw new Error('Server is busy, try again.');
        }

        // Step 4: Stream SSE
        $('#ame-vto-progress-fill').css('width', '60%');
        $('#ame-vto-loading-stage').text('Synthesizing photorealistic fabric drape...');

        var streamRes = await fetch('https://yisol-idm-vton.hf.space/queue/data?session_hash=' + sessionHash, { signal: signal });
        var reader = streamRes.body.getReader();
        var decoder = new TextDecoder();
        var generatedUrl = null;

        while (true) {
          var chunk = await reader.read();
          if (chunk.done) break;
          var text = decoder.decode(chunk.value, { stream: true });
          var lines = text.split('\n');

          for (var i = 0; i < lines.length; i++) {
            var line = lines[i];
            if (line.startsWith('data:')) {
              var raw = line.replace(/^data:\s*/, '').trim();
              if (!raw) continue;
              try {
                var eventData = JSON.parse(raw);
                if (eventData.msg === 'process_starts') {
                  $('#ame-vto-progress-fill').css('width', '75%');
                  $('#ame-vto-loading-stage').text('Generating tailored runway fit...');
                } else if (eventData.msg === 'process_completed') {
                  $('#ame-vto-progress-fill').css('width', '100%');
                  if (eventData.output && eventData.output.data && eventData.output.data[0]) {
                    var outItem = eventData.output.data[0];
                    generatedUrl = outItem.url || (outItem.image && outItem.image.url) || (typeof outItem === 'string' ? outItem : null);
                  }
                  break;
                } else if (eventData.msg === 'error' || eventData.success === false) {
                  throw new Error('Server is busy, try again.');
                }
              } catch (parseErr) {
                // Ignore parse errors on heartbeats
              }
            }
          }

          if (generatedUrl) break;
        }

        if (generatedUrl) {
          setTimeout(function () {
            self.displayResult(generatedUrl);
          }, 300);
        } else {
          throw new Error('Server is busy, try again.');
        }
      } catch (err) {
        if (err.name === 'AbortError') return;
        self.showScreen('setup');
        self.showError(err.message || 'Server is busy, try again.');
      } finally {
        self.activeAbortController = null;
      }
    },

    displayResult: function (resultUrl) {
      var self = this;
      $('#ame-vto-final-result-img').attr('src', resultUrl);
      $('#ame-vto-final-original-img').attr('src', self.personImage);

      $('#ame-vto-side-original-img').attr('src', self.personImage);
      $('#ame-vto-side-garment-img').attr('src', self.garmentImage);
      $('#ame-vto-side-result-img').attr('src', resultUrl);

      $('#ame-vto-single-result-img').attr('src', resultUrl);

      // Reset slider to 50%
      self.sliderPos = 50;
      $('#ame-vto-clip-layer').css('width', '50%');
      $('#ame-vto-slider-handle').css('left', '50%');

      self.showScreen('result');
    }
  };

  $(document).ready(function () {
    VTO.init();
  });

})(jQuery);
