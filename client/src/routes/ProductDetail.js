import { h, Component } from 'preact';
import { Link, route } from 'preact-router';
import { Layout, Button, InputNumber, Table, MessageBox } from 'element-react';

import ProductList from '../components/ProductList';

import utils from '../utils';
import cart from '../utils/cart';

export default class ProductDetail extends Component{
  state = {
    product: null,
    relativeCategory: null,
    relativeBranch: null,
    selectedColor: null
  }
  specColumns = [
    { label: "Tên", prop: "name" },
    { label: "Giá trị", prop: "value" }
  ]
  quantityRef = null

  constructor(props){
    super(props);
    this.fetchData(props);
  }
  componentWillReceiveProps(nextProps, nextState){
    this.fetchData(nextProps);
  }
  fetchData = (props) => {
    utils.fetch(`product/one?url=${props.productUrl}`, product => {
      window.scrollTo(0,0);
      const DETAIL = JSON.parse(product['DETAIL']);
      const selectedColor = DETAIL.images.selected;
      //console.log(selectedColor);

      this.setState({
        product: {
          ...product,
          DETAIL,
        },
        selectedColor
      })
    });
    utils.fetchProduct(`by?category=${props.category}&&ipp=5&&page=1`, res => this.setState({ relativeCategory: res.data }));
    if (props.branch !== 'tat-ca'){
      utils.fetchProduct(`by?branch=${props.branch}&&ipp=5&&page=1`, res => this.setState({ relativeBranch: res.data }));
    }
  }
  addToCart = async e => {
    if (this.props.isAuthen){
      //console.log();
      let username = this.props.user['USERNAME'];
      let { product, selectedColor } = this.state;
      let { category, branch } = this.props;
      
      //console.log(username, product);
      
      cart.updateItem(username, category, branch, product, this.quantityRef.state.value, selectedColor);
    }
    else {
      let action = await MessageBox.msgbox({
        title: 'Thông báo',
        message: 'Bạn phải đăng nhập trước đã!', 
        confirmButtonText: 'Đăng nhập ngay',
        cancelButtonText: 'Hủy bỏ',
        showCancelButton: true
      });
  
      if (action === 'confirm'){
        route('/dang-nhap');
      }
    }
  }
  render(){
    const { category, branch, categories, branchs } = this.props
    const { product, relativeBranch, relativeCategory, selectedColor } = this.state;
    let detail, imgList, spec = null;
    
    if (product){
      detail = product['DETAIL'];
      imgList = detail.images.items;
      //selectedColor = detail.images.selected;
      if (detail.spec){
        spec = detail.spec.map(sp => ({ name: sp[0], value: sp[1] }));
      }
    }
    return (
      product &&
      <div class="container pt-30">
        <Layout.Row>
          <Layout.Col sm={10} className="mb-30">
            <Layout.Col xs={6} sm={6}>
            {
              Object.keys(imgList).map(color => (
                <Layout.Row className="d-flex fl-x-center mb-15 pointer hover-light">
                  <div>
                    <img src={`../assets/images/details/${category}/${branch}/${imgList[color]}`} alt={color} width="64" 
                    onClick={e => this.setState({ selectedColor: color })} />
                  </div>
                </Layout.Row>
              ))
            }
            </Layout.Col>
            <Layout.Col xs={18} sm={18}>
              <img src={`../assets/images/details/${category}/${branch}/${imgList[selectedColor]}`} alt="main" style="width: 90%" />
            </Layout.Col>
          </Layout.Col>
          <Layout.Col sm={14}>
            <h2>{product['NAME']} ({selectedColor})</h2>
            <p>{product['SUBTITLE']}</p>
            <h1 class="text-danger">{utils.formatMoney(product['PRICE'])}</h1>
            <p className="text-dark mt-5 width-100">
              <i class="fa fa-eye"></i> {product['VIEW']}
              <i class="fa fa-dollar ml-10"></i> {product['SOLD']}
              <i class="fa fa-database ml-10"></i> {product['QUANTITY']}
            </p>
            <hr class="bd-0 bd-t-1 mb-30" />
            <h3>Mô tả:</h3>
            <p class="fs-17">{detail['desc']}</p>
            <hr class="bd-0 bd-t-1 mb-30" />
            <p class="fs-16 mb-30">
              Chọn số lượng: <InputNumber ref={el => this.quantityRef = el} className="ml-10" defaultValue={1} min="1" max={product['QUANTITY']}></InputNumber>
              <small class="text-danger ml-20">Chỉ còn lại {product['QUANTITY']} sản phẩm!</small>
            </p>
            <Button type="danger" size="large" className="mr-10 font-weight-bold">Mua ngay</Button>
            <Button type="primary" size="large" className="font-weight-bold" onClick={this.addToCart}>Thêm vào giỏ hàng</Button>
          </Layout.Col>
        </Layout.Row>
        <h2 class="mt-30">Thông số kỹ thuật</h2>
        <hr class="bd-0 bd-t-1 mb-30" />
        <Layout.Row>
          {
            spec ?
            <Table columns={this.specColumns} data={spec} stripe={true} /> :
            <p class="text-danger">Đang cập nhật...</p>
          }
        </Layout.Row>
        <h2 class="mt-30 pt-30">Sản phẩm cùng loại</h2>
        <hr class="bd-0 bd-t-1 mb-30" />
        <Layout.Row>
        {
          relativeCategory &&
          <ProductList categories={categories} branchs={branchs} products={relativeCategory}></ProductList>
        }
        </Layout.Row>
        {
          relativeBranch &&
          <div>
            <h2 class="mt-30 pt-30">Sản phẩm cùng nhà sản xuất</h2>
            <hr class="bd-0 bd-t-1 mb-30" />
            <Layout.Row>
              <ProductList categories={categories} branchs={branchs} products={relativeBranch}></ProductList>
            </Layout.Row>
          </div>
        }
      </div>
    )
  }
}