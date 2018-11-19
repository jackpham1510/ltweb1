import { h } from 'preact';
import { Link } from 'preact-router';
import { Layout, Card } from 'element-react';

import utils from '../utils';

export default ({products, categories, branchs}) => (
  <Layout.Row>
  {
    products.map(p => (
      <Layout.Col xs={12} sm={6}>
        <Card className="card-body d-flex fl-x-center p-0">
          <Link href={utils.resolveProductUrl(p, categories, branchs)} style={{ textDecoration: 'none' }}>
            <div class="card-body__img-container d-flex fl-x-center fl-y-center">
              <img src={`../assets/images/details/${utils.resolveProductImg(p, categories, branchs)}`} title={p['NAME']} />
            </div>           
            <div class="card-body__desc mt-30 bd-t-1" title={p['NAME']}>
              <h3 class="fs-14 text-dark font-weight-bold mb-5 card-body__title width-100">{p['NAME']}</h3>
              <div className="bottom clearfix">
                <p className="fs-12 text-dark my-5 width-100">{p['SUBTITLE']}</p>
                <p className="fs-16 text-danger fw-bold my-5 width-100">{utils.formatMoney(p['PRICE'])}</p>
                <p className="fs-12 text-dark mt-5 width-100">
                  <i class="fa fa-eye"></i> {p['VIEW']}
                  <i class="fa fa-dollar ml-10"></i> {p['SOLD']}
                  <i class="fa fa-database ml-10"></i> {p['QUANTITY']}
                </p>
              </div>
            </div>
          </Link>
        </Card>
      </Layout.Col>
    ))
  }
  </Layout.Row>
)