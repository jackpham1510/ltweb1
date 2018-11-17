import { h, Component } from 'preact';
import { Layout } from 'element-react';
import { Link } from 'preact-router';

import config from '../../../config.json';
import utils from '../utils';

import CenterHead from '../components/CenterHead';
import ProductList from '../components/ProductList';
import BranchList from '../components/BranchList';
import Banner from '../components/Banner';

export default class Home extends Component{
  state = {
    topsolds: null,
    topviews: null,
    topnews: null
  }
  
  constructor(props){
    super(props);
    utils.fetchProduct('top/sold?t=12', topsolds => this.setState({topsolds}));
    utils.fetchProduct('top/view?t=12', topviews => this.setState({topviews}));
    utils.fetchProduct('top/new?t=12', topnews => this.setState({topnews}));
  }
  render(){
    //console.log(this.props);
    const { branchs, categories } = this.props;
    const { topnews, topsolds, topviews } = this.state;
    return (
      <div class="container">
        <Banner></Banner>
        <BranchList></BranchList>
        <Layout.Row>
          <CenterHead type="danger">
            <i class="fa fa-heart mr-10"></i>
            Bán chạy nhất
          </CenterHead>
          {
            topsolds &&
            <ProductList products={topsolds} categories={categories} branchs={branchs}>
            </ProductList>
          }
        </Layout.Row>
        <Layout.Row className="mt-40">
          <CenterHead type="danger">
            <i class="fa fa-fire mr-10"></i>
            Mới nhất
          </CenterHead>
          {
            topnews &&
            <ProductList products={topnews} categories={categories} branchs={branchs}>
            </ProductList>
          }
        </Layout.Row>
        <Layout.Row className="mt-40">
          <CenterHead type="danger">
            <i class="fa fa-eye mr-10"></i>
            Xem nhiều nhất
          </CenterHead>
          {
            topviews &&
            <ProductList products={topviews} categories={categories} branchs={branchs}>
            </ProductList>
          }
        </Layout.Row>
      </div>
    )
  }
}