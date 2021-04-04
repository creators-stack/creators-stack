import './bootstrap';

import 'alpinejs';
import 'lightgallery.js';
import 'lg-fullscreen.js';
import 'lg-zoom.js';
import 'lg-pager.js';
import 'lg-autoplay.js';
import hljs from 'highlight.js';

import LightGallery from "./components/lightgallery";
import './components/videoPlayer';
import './components/event';

hljs.highlightAll();
window.hljs = hljs;

new LightGallery();
