import { h } from 'preact';
import { Link } from 'preact-router';
import { Layout } from 'element-react';

export default () => (
  <Layout.Row className="pt-20 mb-30">
    <Layout.Col span={4} style={{ border: '1px solid #f1f1f1' }}>
      <Link href="/san-pham/dien-thoai/apple">
        <img src="../assets/images/iphone-logo.jpg" alt="iPhone logo" />
      </Link>
    </Layout.Col>
    <Layout.Col span={4} style={{ border: '1px solid #f1f1f1' }}>
      <Link href="/san-pham/dien-thoai/samsung">
        <img src="../assets/images/samsung-logo.jpg" alt="Samsung logo" />
      </Link>
    </Layout.Col>
    <Layout.Col span={4} style={{ border: '1px solid #f1f1f1' }}>
      <Link href="/san-pham/dien-thoai/xiaomi">
        <img src="../assets/images/xiaomi-logo.png" alt="Xiaomi logo" />
      </Link>
    </Layout.Col>
    <Layout.Col span={4} style={{ border: '1px solid #f1f1f1' }}>
      <Link href="/san-pham/dien-thoai/oppo">
        <img src="../assets/images/oppo-logo.jpg" alt="Oppo logo" />
      </Link>
    </Layout.Col>
    <Layout.Col span={4} style={{ border: '1px solid #f1f1f1' }}>
      <Link href="/san-pham/dien-thoai/asus">
        <img src="../assets/images/asus-logo.png" alt="Asus logo" />
      </Link>  
    </Layout.Col>
    <Layout.Col span={4} style={{ border: '1px solid #f1f1f1', height: '54px' }} className="d-flex fl-x-center fl-y-center">
      <Link href="/san-pham/dien-thoai" class="text-primary fw-bold">Tất cả</Link>
    </Layout.Col>
  </Layout.Row>
)