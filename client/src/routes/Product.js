import { h, Component } from 'preact';
import { Link, route } from 'preact-router';
import { Layout, Pagination } from 'element-react';

import utils from '../utils';
import config from '../../../config.json';

import BranchList from '../components/BranchList';
import ProductList from '../components/ProductList';

export default class Product extends Component{
  state = {
    products: null,
    pagination: null,
    url: null
  }
  constructor(props){
    super(props);
    this.fetchData(props);
  }
  componentWillReceiveProps(nextProps, nextState){
    this.fetchData(nextProps);
  }
  render(){
    const { branchs, categories } = this.props;
    const { products, pagination } = this.state;
    return (
      <div class="container">
        <BranchList branchs={branchs}></BranchList>
        <Layout.Row>
          {
            products &&
            <ProductList products={products} categories={categories} branchs={branchs}>
            </ProductList>
          }
        </Layout.Row>
        <Layout.Row className="mt-40 pt-20 d-flex fl-x-center">
        {
          pagination &&
          <Pagination layout="prev, pager, next" {...pagination} pageSize={config.product.itemPerPage} onCurrentChange={this.changePage}/>
        }
        </Layout.Row>
      </div>
    )
  }
  fetchData = (props) => {
    //console.log(props);
    const { category, branch, name, url } = props;
    const params = utils.parseUrl(url);
    let page = 1;
    if ('page' in params){
      page = params.page;
    }
    let apiPath = `by?page=${page}&category=${category}`;
    if (branch !== ''){
      apiPath += `&branch=${branch}`;
    }
    utils.fetchProduct(apiPath, res => {
      window.scrollTo(0, 0);
      this.setState({ 
        products: res.data,
        pagination: {
          currentPage: res.active,
          total: parseInt(res.total)
        },
        url
      });
    });
  }
  changePage = page => {
    console.log(this.state.url, page);
    route(this.state.url.split('?')[0] + '?page=' + page);
  }
}