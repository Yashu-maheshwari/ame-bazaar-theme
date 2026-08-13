<?php
/**
 * AME Bazaar inner-page and mobile UI polish.
 * Loaded automatically as a WordPress must-use plugin.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_enqueue_scripts', function () {
    $css = <<<'CSS'
/* AME Bazaar — inner pages + mobile navigation polish */
.ame-contact-hero-header,.ame-about-hero-header,.ame-faq-hero-header{position:relative!important;overflow:hidden!important;background:linear-gradient(135deg,#00182f 0%,#002b50 58%,#063c67 100%)!important}
.ame-contact-hero-header:before,.ame-about-hero-header:before,.ame-faq-hero-header:before{content:"";position:absolute;inset:0;pointer-events:none;background:radial-gradient(circle at 15% 20%,rgba(245,158,11,.12),transparent 32%),radial-gradient(circle at 85% 80%,rgba(255,255,255,.07),transparent 35%)}
.ame-contact-hero-header .ame-bazaar-container,.ame-about-hero-header .ame-bazaar-container,.ame-faq-hero-header .ame-bazaar-container{position:relative;z-index:1}
.ame-contact-hero-header h1,.ame-about-hero-header h1,.ame-faq-hero-header h1{color:#fff!important;text-shadow:0 4px 20px rgba(0,0,0,.24)!important}
.ame-contact-hero-header p,.ame-about-hero-header p,.ame-faq-hero-header p{color:rgba(255,255,255,.9)!important}
.ame-contact-hero-header a,.ame-about-hero-header a,.ame-faq-hero-header a{color:#fff!important}
.ame-contact-hero-header p:last-child a{display:inline-flex!important;align-items:center;justify-content:center;min-height:42px;padding:.72rem 1.25rem;border-radius:999px!important;background:#fff!important;color:#002347!important;text-decoration:none!important;font-weight:800!important;font-size:.8rem!important;letter-spacing:.045em;box-shadow:0 9px 24px rgba(0,0,0,.18);transition:transform .2s ease,box-shadow .2s ease}
.ame-contact-hero-header p:last-child a:hover{transform:translateY(-2px);box-shadow:0 13px 30px rgba(0,0,0,.24)}
.ame-desktop-menu-luxury{gap:.5rem!important;align-items:center!important}
.ame-desktop-menu-luxury li{min-width:0!important;display:flex!important;align-items:center!important}
.ame-desktop-menu-luxury a{white-space:nowrap!important;flex-shrink:0!important}
@media(max-width:1023px){
 .ame-header-luxury-wrapper{min-height:auto!important;padding:.65rem .5rem .7rem!important}
 .ame-header-luxury-inner{display:flex!important;flex-wrap:wrap!important;justify-content:center!important;gap:.45rem!important;min-height:0!important}
 .ame-header-luxury-center{order:-1!important;flex:0 0 100%!important;display:flex!important;justify-content:center!important}
 .ame-header-luxury-left{width:100%!important;min-width:0!important;display:flex!important;justify-content:center!important}
 .ame-desktop-nav-luxury{display:block!important;width:100%!important;min-width:0!important;overflow:hidden!important}
 .ame-desktop-menu-luxury{display:flex!important;flex-wrap:nowrap!important;justify-content:flex-start!important;gap:.25rem!important;width:100%!important;min-width:0!important;padding:4px!important;overflow-x:auto!important;overflow-y:hidden!important;scrollbar-width:none!important;white-space:nowrap!important}
 .ame-desktop-menu-luxury::-webkit-scrollbar{display:none!important}
 .ame-desktop-menu-luxury li{flex:0 0 auto!important}
 .ame-desktop-menu-luxury a{font-size:11px!important;min-height:32px!important;padding:7px 10px!important;letter-spacing:.045em!important}
 .ame-header-luxury-right{flex:0 0 100%!important;display:flex!important;justify-content:center!important;gap:.45rem!important}
 .ame-luxury-pill-btn{padding:.58rem 1rem!important;font-size:11px!important}
}
@media(max-width:782px){
 body.admin-bar .ame-header-luxury-wrapper{top:46px!important}
 .ame-logo-img{max-height:40px!important}
 .ame-header-luxury-right .ame-luxury-pill-btn{display:none!important}
 .ame-luxury-action-btn{width:34px!important;height:34px!important}
 .ame-contact-page-main,.ame-about-page-main,.ame-faq-page-main,.ame-ai-advisor-main-shell{overflow:hidden!important}
 .ame-contact-hero-header,.ame-about-hero-header,.ame-faq-hero-header{padding:3.2rem 1rem!important}
 .ame-contact-hero-header h1,.ame-about-hero-header h1,.ame-faq-hero-header h1{font-size:clamp(2rem,9vw,2.7rem)!important;line-height:1.08!important}
 .ame-contact-hero-header p,.ame-about-hero-header p,.ame-faq-hero-header p{font-size:.98rem!important}
 .ame-contact-page-main .ame-bazaar-container,.ame-about-page-main .ame-bazaar-container,.ame-faq-page-main .ame-bazaar-container{width:min(100% - 24px,1050px)!important;margin-inline:auto!important}
 .ame-contact-grid,.ame-about-row-split,.ame-about-pillars-grid{grid-template-columns:1fr!important;gap:1.25rem!important}
 .ame-contact-page-main .ame-contact-info-panel{padding:1.35rem!important;gap:1.25rem!important}
 .ame-about-founder-block{padding:1.6rem 1.25rem!important}
 .ame-faq-search-wrapper{padding:1rem!important}
 .ame-faq-chips-container{justify-content:flex-start!important;overflow-x:auto!important;flex-wrap:nowrap!important;padding-bottom:.3rem!important;scrollbar-width:none!important}
 .ame-faq-chips-container::-webkit-scrollbar{display:none!important}
 .ame-faq-chip{flex:0 0 auto!important;white-space:nowrap!important}
}
.ame-ai-advisor-main-shell{background:linear-gradient(180deg,#f7fafc 0%,#fff 38%,#f8f7f3 100%)!important}
.ame-ai-advisor-main-shell .ame-advisor-title{font-family:'Playfair Display',serif!important;font-weight:600!important}
.ame-ai-advisor-main-shell .ame-advisor-layout-grid{grid-template-columns:minmax(230px,.72fr) minmax(0,1.65fr)!important;align-items:start!important;gap:1.5rem!important}
.ame-ai-advisor-main-shell .ame-advisor-preferences-panel{position:sticky;top:105px!important;padding:1.25rem!important;border-radius:18px!important;box-shadow:0 14px 38px rgba(0,35,71,.08)!important}
.ame-ai-advisor-main-shell .ame-advisor-console-box{border-radius:18px!important;box-shadow:0 18px 45px rgba(0,35,71,.11)!important}
.ame-ai-advisor-main-shell .ame-console-header-bar{background:linear-gradient(135deg,#00182f,#002f57)!important}
.ame-ai-advisor-main-shell .ame-pref-select{min-height:44px!important;border-radius:12px!important}
@media(max-width:900px){.ame-ai-advisor-main-shell .ame-advisor-layout-grid{grid-template-columns:1fr!important}.ame-ai-advisor-main-shell .ame-advisor-preferences-panel{position:static!important}}
@media(max-width:782px){
 .ame-ai-advisor-main-shell .ame-bazaar-container{width:min(100% - 20px,1100px)!important;margin-inline:auto!important}
 .ame-ai-advisor-main-shell .ame-advisor-title{font-size:clamp(2rem,9vw,2.65rem)!important;line-height:1.08!important}
 .ame-ai-advisor-main-shell .ame-advisor-desc{font-size:.94rem!important}
 .ame-ai-advisor-main-shell .ame-advisor-preferences-panel{display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr));gap:.8rem!important}
 .ame-ai-advisor-main-shell .ame-advisor-preferences-panel h3,.ame-ai-advisor-main-shell .ame-advisor-preferences-panel>button{grid-column:1/-1!important}
 .ame-ai-advisor-main-shell .ame-advisor-preferences-panel>div{min-width:0}
 .ame-ai-advisor-main-shell .ame-console-header-bar{padding:1rem 1.1rem!important}
 .ame-ai-advisor-main-shell #ame-advisor-chat-screen{padding:1.1rem!important;max-height:420px!important}
 .ame-ai-advisor-main-shell .ame-chat-bubble-agent{max-width:95%!important}
}
CSS;
    wp_register_style( 'ame-bazaar-inner-polish', false );
    wp_enqueue_style( 'ame-bazaar-inner-polish' );
    wp_add_inline_style( 'ame-bazaar-inner-polish', $css );
}, 100 );
