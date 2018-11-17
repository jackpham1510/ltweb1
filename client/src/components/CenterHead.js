import { h } from 'preact';

export default ({children, type}) => (
  <div class="title d-flex fl-x-center relative mb-40">
    <hr class={`bd-${type} z-0 absolute width-100`} style="top: 35px" />
    <h2 class={`bg-${type} text-white py-10 px-20 d-inline-block z-2 bdr-8`}>{children}</h2>
  </div>
);