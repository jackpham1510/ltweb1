import { h, Component } from 'preact';

import ProductList from '../components/ProductList';

import { Layout, Pagination } from 'element-react';

import utils from '../utils';
import config from '../../../config.json';

export default class SearchResult extends Component {
  state = {
    products: null,
    pagination: null,
    input: ""
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
    const { products, pagination, input } = this.state;
    return (
      <div class="container">
        <Layout.Row>
            <div>
              {
                pagination &&
                <h2>Tìm được <span class="text-danger">{pagination.total}</span> sản phẩm với từ khóa "<span class="text-danger">{input}</span>"</h2>
              }
              <hr class="mb-40 bd-0 bd-t-1" />
              {
                products &&
                <ProductList products={products} categories={categories} branchs={branchs}>
                </ProductList>
              }
            </div>
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
    const { url } = props;
    const params = utils.parseUrl(url);
    if ('input' in params){
      let page = 1;
      if ('page' in params){
        page = params.page;
      }
      
      let apiPath = `search?page=${page}&input=${params.input}`;
      console.log('api', apiPath);
      
      utils.fetchProduct(apiPath, res => {
        window.scrollTo(0, 0);
        this.setState({ 
          products: res.data,
          input: params.input,
          pagination: {
            currentPage: res.active,
            total: parseInt(res.total)
          }
        });
      });
    }
  }
  changePage = page => {
    //console.log(this.props.url, page);
    utils.routeParams(this.props.url, { page });
  }
}