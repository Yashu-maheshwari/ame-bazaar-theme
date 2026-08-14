<?php
/**
 * AME Bazaar mobile header final visual polish.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_enqueue_scripts', function () {
    $css = <<<'CSS'
@media (max-width:1023px){
  .ame-header-luxury-wrapper{
    background:linear-gradient(135deg,#f8fafc 0%,#ffffff 48%,#eef5fb 100%) !important;
    border-bottom:1px solid rgba(0,35,71,.12)!important;
    box-shadow:0 8px 28px rgba(0,35,71,.08)!important;
    padding:8px 10px 10px!important;
  }
  .ame-header-luxury-inner{
    display:grid!important;
    grid-template-columns:minmax(0,1fr) 64px minmax(0,1fr)!important;
    grid-template-rows:54px 44px!important;
    gap:6px!important;
    align-items:center!important;
    width:100%!important;
    min-height:104px!important;
  }
  .ame-mobile-pill-zone{
    grid-column:1!important;
    grid-row:1!important;
    display:flex!important;
    align-items:center!important;
    justify-content:flex-start!important;
    gap:6px!important;
    min-width:0!important;
  }
  .ame-mobile-pill-zone .ame-luxury-pill-btn{
    display:inline-flex!important;
    align-items:center!important;
    justify-content:center!important;
    min-height:34px!important;
    padding:8px 10px!important;
    border-radius:999px!important;
    font-size:8px!important;
    font-weight:800!important;
    letter-spacing:.035em!important;
    white-space:nowrap!important;
    flex:0 1 auto!important;
    background:#002347!important;
    color:#fff!important;
    border:1px solid rgba(0,35,71,.18)!important;
    box-shadow:0 7px 18px rgba(0,35,71,.14)!important;
  }
  .ame-header-luxury-center{
    grid-column:2!important;
    grid-row:1!important;
    display:flex!important;
    justify-content:center!important;
    align-items:center!important;
    z-index:10!important;
    width:64px!important;
    height:64px!important;
    justify-self:center!important;
    align-self:center!important;
  }
  .ame-logo-link{
    width:60px!important;
    height:60px!important;
    display:flex!important;
    align-items:center!important;
    justify-content:center!important;
    background:#fff!important;
    border:1px solid rgba(0,35,71,.10)!important;
    border-radius:18px!important;
    box-shadow:0 12px 28px rgba(0,35,71,.14),0 2px 7px rgba(245,158,11,.12)!important;
  }
  .ame-logo-img{
    max-width:48px!important;
    max-height:48px!important;
    width:auto!important;
    object-fit:contain!important;
  }
  .ame-header-luxury-right{
    grid-column:3!important;
    grid-row:1!important;
    display:flex!important;
    align-items:center!important;
    justify-content:flex-end!important;
    gap:5px!important;
    min-width:0!important;
  }
  .ame-header-luxury-right .ame-luxury-pill-btn{display:none!important}
  .ame-header-luxury-right .ame-luxury-action-btn{
    width:34px!important;height:34px!important;min-width:34px!important;min-height:34px!important;
    padding:0!important;display:grid!important;place-items:center!important;
    background:#fff!important;border:1px solid rgba(0,35,71,.07)!important;
    border-radius:50%!important;box-shadow:0 6px 15px rgba(0,35,71,.08)!important;
  }
  .ame-header-luxury-right .ame-luxury-icon{width:16px!important;height:16px!important}
  .ame-header-luxury-left{
    grid-column:1 / -1!important;
    grid-row:2!important;
    width:100%!important;
    min-width:0!important;
    display:flex!important;
    justify-content:center!important;
    align-items:center!important;
  }
  .ame-luxury-menu-toggle{display:none!important}
  .ame-desktop-nav-luxury{
    display:flex!important;
    width:100%!important;
    max-width:100%!important;
    justify-content:center!important;
    overflow-x:auto!important;
    overflow-y:hidden!important;
    white-space:nowrap!important;
    scrollbar-width:none!important;
    background:rgba(255,255,255,.78)!important;
    border:1px solid rgba(0,35,71,.10)!important;
    border-radius:20px!important;
    box-shadow:0 5px 16px rgba(0,35,71,.06)!important;
  }
  .ame-desktop-nav-luxury::-webkit-scrollbar{display:none!important}
  .ame-desktop-nav-luxury a{
    flex:0 0 auto!important;
    font-size:9px!important;font-weight:800!important;
    padding:10px 10px!important;
    white-space:nowrap!important;
    letter-spacing:.025em!important;
  }
  /* Premium blue landing feel for the actual hero, without changing video content. */
  .ame-hero-section,.ame-homepage-hero,.ame-hero-banner,.ame-hero-wrap{
    background:#002b50!important;
    position:relative!important;
  }
  .ame-hero-section::after,.ame-homepage-hero::after,.ame-hero-banner::after,.ame-hero-wrap::after{
    content:"";position:absolute;inset:0;pointer-events:none;
    background:linear-gradient(90deg,rgba(0,35,71,.54),rgba(0,35,71,.14) 55%,rgba(0,35,71,.24))!important;
    z-index:1;
  }
  .ame-hero-section video,.ame-homepage-hero video,.ame-hero-banner video,.ame-hero-wrap video{position:relative;z-index:0}
  .ame-hero-section .ame-hero-content,.ame-homepage-hero .ame-hero-content,.ame-hero-banner .ame-hero-content,.ame-hero-wrap .ame-hero-content{position:relative;z-index:2}
}
@media(max-width:390px){
  .ame-header-luxury-inner{grid-template-columns:minmax(0,1fr) 58px minmax(0,1fr)!important;column-gap:4px!important}
  .ame-header-luxury-center,.ame-logo-link{width:56px!important;height:56px!important}
  .ame-logo-img{max-width:44px!important;max-height:44px!important}
  .ame-mobile-pill-zone{gap:4px!important}
  .ame-mobile-pill-zone .ame-luxury-pill-btn{padding:7px 8px!important;font-size:7.5px!important}
  .ame-header-luxury-right .ame-luxury-action-btn{width:30px!important;height:30px!important;min-width:30px!important;min-height:30px!important}
  .ame-header-luxury-right .ame-luxury-icon{width:15px!important;height:15px!important}
  .ame-desktop-nav-luxury a{font-size:8px!important;padding-inline:8px!important}
}
CSS;
    wp_register_style( 'ame-bazaar-mobile-header-premium-final', false );
    wp_enqueue_style( 'ame-bazaar-mobile-header-premium-final' );
    wp_add_inline_style( 'ame-bazaar-mobile-header-premium-final', $css );
}, 1300 );
