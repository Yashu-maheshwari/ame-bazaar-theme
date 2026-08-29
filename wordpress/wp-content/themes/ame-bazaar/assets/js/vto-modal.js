/**
 * AME Bazaar Virtual Try-On (VTO) Modal & Fitting Engine.
 * Manages customer portrait uploads, camera capture, asynchronous IDM-VTON
 * job polling, split comparison slider, and high-definition result viewing.
 */
(function ($) {
  'use strict';

  var VTO = {
    modalRoot: null,
    personImage: null,
    garmentImage: null,
    selectedCategory: 'upper_body',
    currentJobId: null,
    pollTimer: null,
    sliderPos: 50,
    isDraggingSlider: false,

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
      if (this.pollTimer) {
        clearInterval(this.pollTimer);
        this.pollTimer = null;
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

      // Open camera stream in a video element or trigger system camera capture
      var video = document.createElement('video');
      video.setAttribute('playsinline', '');
      video.setAttribute('autoplay', '');

      navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
        .then(function (stream) {
          // Modal for camera snapshot
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
          // Fallback to standard input
          $('#ame-vto-file-input').trigger('click');
        });
    },

    showError: function (msg) {
      $('#ame-vto-error-text').text(msg || 'An error occurred. Please try again.');
      $('#ame-vto-error-banner').show();
    },

    startFittingJob: function () {
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
      $('#ame-vto-loading-stage').text('Submitting tensors to IDM-VTON neural fitting pipeline...');

      var restUrl = (typeof ameBazaarVTO !== 'undefined' && ameBazaarVTO.restUrl) ? ameBazaarVTO.restUrl : '/wp-json/ame/v1/';
      var nonce = (typeof ameBazaarVTO !== 'undefined' && ameBazaarVTO.nonce) ? ameBazaarVTO.nonce : '';
      var productId = (typeof ameBazaarVTO !== 'undefined' && ameBazaarVTO.productId) ? ameBazaarVTO.productId : 0;

      $.ajax({
        url: restUrl + 'try-on',
        method: 'POST',
        headers: {
          'X-WP-Nonce': nonce
        },
        contentType: 'application/json',
        data: JSON.stringify({
          person_image: self.personImage,
          garment_image: self.garmentImage,
          category: self.selectedCategory,
          product_id: productId
        }),
        success: function (response) {
          if (response && response.job_id) {
            self.currentJobId = response.job_id;
            self.pollJobStatus(response.job_id);
          } else {
            self.showScreen('setup');
            self.showError(response.message || 'Server is busy, try again.');
          }
        },
        error: function (xhr) {
          self.showScreen('setup');
          var errJson = xhr.responseJSON;
          var msg = (errJson && errJson.message) ? errJson.message : 'Server is busy, try again.';
          self.showError(msg);
        }
      });
    },

    pollJobStatus: function (jobId) {
      var self = this;
      var restUrl = (typeof ameBazaarVTO !== 'undefined' && ameBazaarVTO.restUrl) ? ameBazaarVTO.restUrl : '/wp-json/ame/v1/';
      var pollAttempts = 0;
      var maxPolls = 25; // 50 seconds max

      if (self.pollTimer) {
        clearInterval(self.pollTimer);
      }

      self.pollTimer = setInterval(function () {
        pollAttempts++;
        var progressPct = Math.min(92, 20 + (pollAttempts * 3.5));
        $('#ame-vto-progress-fill').css('width', progressPct + '%');

        $.ajax({
          url: restUrl + 'try-on/' + encodeURIComponent(jobId),
          method: 'GET',
          success: function (job) {
            if (!job) return;

            if (job.stage) {
              $('#ame-vto-loading-stage').text(job.stage);
            }

            if (job.status === 'completed' && job.result_url) {
              clearInterval(self.pollTimer);
              self.pollTimer = null;
              $('#ame-vto-progress-fill').css('width', '100%');
              setTimeout(function () {
                self.displayResult(job.result_url);
              }, 400);
            } else if (job.status === 'error') {
              clearInterval(self.pollTimer);
              self.pollTimer = null;
              self.showScreen('setup');
              self.showError(job.message || 'Server is busy, try again.');
            }
          },
          error: function () {
            if (pollAttempts >= maxPolls) {
              clearInterval(self.pollTimer);
              self.pollTimer = null;
              self.showScreen('setup');
              self.showError('Server is busy, try again.');
            }
          }
        });

        if (pollAttempts >= maxPolls) {
          clearInterval(self.pollTimer);
          self.pollTimer = null;
          self.showScreen('setup');
          self.showError('Server is busy, try again.');
        }
      }, 2000);
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
