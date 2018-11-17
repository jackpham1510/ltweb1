import { h } from 'preact';
import { Link } from 'preact-router';
import { Layout } from 'element-react';

export default ({branchs}) => (
  <Layout.Row className="pt-20 mb-30 branch-list">
    {
      Object.keys(branchs).map(k => (
        <Layout.Col sm={8} xs={12} style={{ border: '1px solid #f1f1f1' }} className="d-flex fl-x-center fl-y-center py-10">  
          <Link href={`/san-pham/dien-thoai/${branchs[k]['URL']}`}>
            <img src={`../assets/images/logo/${branchs[k]['URL']}.jpg`} alt={`${branchs[k]['URL']} logo`} class="branch" />
          </Link>
        </Layout.Col>
      ))
    }
    <Layout.Col sm={8} xs={12} style={{ border: '1px solid #f1f1f1' }} className="d-flex fl-x-center fl-y-center py-10 fs-20 all">
      <Link href="/san-pham/dien-thoai" class="text-primary fw-bold">Tất cả</Link>
    </Layout.Col>
  </Layout.Row>
)