import { h } from 'preact';
import { Layout } from 'element-react';

export default () => (
  <div class="py-20 bg-dark">
    <Layout.Row>
      <Layout.Col span={24} className="py-20 d-flex fl-y-center fl-x-center text-white">
        2018, Phạm Quan Tiến Dũng
      </Layout.Col>
    </Layout.Row>
    <Layout.Row >
      <Layout.Col span={24} className="py-20 d-flex fl-y-center fl-x-center text-white">
        {[
          ['Facebook', 'facebook-square', 'https://fb.com/cpt.jack1998'],
          ['LinkedIn', 'linkedin-square', 'https://www.linkedin.com/in/phamquantiendung/'],
          ['Github', 'github', 'https://github.com/tiendung1510']
        ].map((item, i) => (
          <a href={item[2]} class={"text-white "+ (i !== 2 ? "mr-30" : "")} target="_blank"><i className={`mr-10 fa fa-${item[1]}`}></i> {item[0]}</a>
        ))}
      </Layout.Col>
    </Layout.Row>
  </div>
)