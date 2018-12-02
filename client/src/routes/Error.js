import { h } from 'preact';
import { Button } from 'element-react';
import { Link } from 'preact-router';

export default () => (
  <div class="container d-flex fl-x-center fl-y-center" style="flex-direction: column;">
    <h1 class="text-danger mb-0" style="font-size: 100px"><i class="el-icon-circle-close"></i> 404</h1>
    <h1 class="text-danger" style="font-size: 60px">Oops! Trang này không tồn tại</h1>
    <h2><Link href="/"><Button type="danger">Về trang chủ</Button></Link></h2>
  </div>
)