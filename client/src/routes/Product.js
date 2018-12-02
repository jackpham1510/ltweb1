import { h, Component } from 'preact';
import { Link, route } from 'preact-router';
import { Layout, Pagination, Select } from 'element-react';

import utils from '../utils';
import config from '../../../config.json';

import BranchList from '../components/BranchList';
import ProductList from '../components/ProductList';

export default class Product extends Component{
  state = {
    products: null,
    pagination: null
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
        <Layout.Row className="mb-40 pt-20">
          <span class="mr-10">Sắp xếp:</span>
          <Select className="mr-10 mt-10 mb-10" value="" placeholder="Chọn tiêu chí" onChange={this.orderBy}>
            <Select.Option value="time_stamp-desc" label="Mới nhất"></Select.Option>
            <Select.Option value="sold-desc" label="Bán chạy nhất"></Select.Option>
            <Select.Option value="view-desc" label="Xem nhiều nhất"></Select.Option>
            <Select.Option value="price-desc" label="Giá cao tới thấp"></Select.Option>
            <Select.Option value="price-asc" label="Giá thấp tới cao"></Select.Option>
          </Select>
          <span class="d-inline-block mr-10 mt-10">Chọn mức giá:</span>
          <Link class="d-inline-block mr-10 mt-10 text-primary pointer" onClick={() => this.price(0, 2000000)}>Dưới 2 triệu</Link>
          <Link class="d-inline-block mr-10 mt-10 text-primary pointer" onClick={() => this.price(2000000, 4000000)}>Từ 2 - 4 triệu</Link>
          <Link class="d-inline-block mr-10 mt-10 text-primary pointer" onClick={() => this.price(4000000, 7000000)}>Từ 4 - 7 triệu</Link>
          <Link class="d-inline-block mr-10 mt-10 text-primary pointer" onClick={() => this.price(7000000, 13000000)}>Từ 7 - 13 triệu</Link>
          <Link class="d-inline-block mr-10 mt-10 text-primary pointer" onClick={() => this.price(13000000, 1000000000)}>Trên 13 triệu</Link>
        </Layout.Row>
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
    const { category, branch, url } = props;
    const params = utils.parseUrl(url);
    let page = 1;
    if ('page' in params){
      page = params.page;
    }
    let apiPath = `by?page=${page}&category=${category}`;
    if (branch !== 'tat-ca'){
      apiPath += `&branch=${branch}`;
    }
    if ('price_from' in params && 'price_to' in params){
      apiPath += `&price_from=${params['price_from']}&price_to=${params['price_to']}`;
    }
    if ('orderby' in params && 'mode' in params){
      apiPath += `&orderby=${params.orderby}&mode=${params.mode}`;
    }
    //console.log('api', apiPath);
    utils.fetchProduct(apiPath, res => {
      window.scrollTo(0, 0);
      this.setState({ 
        products: res.data,
        pagination: {
          currentPage: res.active,
          total: parseInt(res.total)
        }
      });
    });
  }
  changePage = page => {
    //console.log(this.props.url, page);
    utils.routeParams(this.props.url, { page });
  }
  orderBy = (value) => {
    //console.log('value', value);
    let [ orderby, mode ] = value.split('-');
    utils.routeParams(this.props.url, { orderby, mode, page: 1 });
  }
  price = (price_from, price_to) => {
    utils.routeParams(this.props.url, { price_from, price_to, page: 1 });
  }
}