import { h, Component } from 'preact';
import { Layout, Table, Button, Notification, InputNumber } from 'element-react';
import { Link } from 'preact-router';

import cart from '../utils/cart';
import utils from '../utils';
import { BuyDialog } from '../components/BuyDialog';

export default class Cart extends Component {
  state = {
    data: [],
    buyDialog: null
  }
  constructor(props){
    super(props);
    this.updateCart();
    cart.subscribe('cart_update', this.updateCart);
  }

  generateParams = (items) => {
    let params = "(";
    
    for (let id in items){
      params += `${id},`;
    }

    return params.slice(0, -1) + ")";
  }

  fetchData = (localItems) => {
    utils.fetchProduct('in?idlist='+this.generateParams(localItems), data => {
      this.setState({ 
        data: data.map(item => ({
          ...item,
          quantity: localItems[item['PRODUCT_ID']].quantity
        })) 
      });
    });
  }

  updateCart = () => {
    let localItems = cart.getItems(this.props.user['USERNAME']);
    
    //console.log(localItems);

    if (Object.keys(localItems) < 1){
      this.setState({ data: [] })
    }
    else {
      this.fetchData(localItems);
    }
  }

  columns = [
    { 
      label: 'Hình ảnh', prop: 'image', align: 'center', width: 150,
      render: (item) => (
        <img width="64" src={'../assets/images/details/'+utils.resolveProductImg(item, this.props.categories, this.props.branchs)} alt={item['NAME']}/>
      )  
    },
    { 
      label: 'Tên sản phẩm', prop: 'productName', align: 'center',
      render: item => (
        <Link href={utils.resolveProductUrl(item, this.props.categories, this.props.branchs)} class="text-primary">{item['NAME']}</Link>
      )
    },
    { 
      label: 'Số lượng', prop: 'quantity', sortable: true, align: 'center', width: 160,
      render: item => (
        <InputNumber defaultValue={item.quantity} size="small" min={1} max={item['QUANTITY']} onChange={quantity => this.updateQuantity(item, quantity)}></InputNumber>
      )
    },
    { 
      label: 'Giá', prop: 'price', align: 'center', sortable: true, width: 200,
      render: item => (
        <span class="text-danger fw-bold">{utils.formatMoney(item['PRICE'])}</span>
      )
    },
    { 
      label: 'Xóa', prop: 'delete', align: 'center', width: 150,
      render: item => (
        <Button type="danger" icon="delete" onClick={e => this.removeItem(item['PRODUCT_ID'])}>Xóa</Button>
      ) 
    }
  ]

  updateQuantity = (item, value) => {
    cart.updateItem(this.props.user['USERNAME'], item, value - item.quantity);
    this.updateCart();
  }

  clearCart = e => {
    if (this.state.data.length > 0){
      cart.clear(this.props.user['USERNAME']);
      Notification({
        title: 'Thông báo',
        message: 'Xóa giỏ hàng thành công!',
        type: 'success'
      });
    }
    else {
      Notification({
        title: 'Thông báo',
        message: 'Giỏ hàng rỗng!',
        type: 'error'
      });
    }
  }

  removeItem = id => {
    cart.removeItem(this.props.user['USERNAME'], id);
    Notification({
      title: 'Thông báo',
      message: 'Xóa sản phẩm khỏi giỏ hàng thành công!',
      type: 'success'
    })
  }

  payment = (total) => {
    if (total === 0) {
      Notification({
        title: 'Thông báo',
        message: 'Giỏ hàng rỗng!',
        type: 'error'
      });
    } 
    else {
      this.setState({
        buyDialog: this.state.data
      });
    }
  }

  render(){
    let { data, buyDialog } = this.state;
    let { user } = this.props;
    let total = utils.totalPrice(data);
    
    return (
      <div class="container">
        <BuyDialog clearCart={true} user={user} items={buyDialog} close={() => this.setState({ buyDialog: null })}></BuyDialog>
        <Layout.Row>
          <h2 align="center" class="text-primary">Giỏ hàng của bạn</h2>
          <hr class="bd-0" style="border-top: 1px solid #20a0ff !important" />
          <div>
            <p class="text-danger float-right pointer" onClick={this.clearCart}><i className="el-icon-delete"></i> Xóa hết giỏ hàng</p>
            <p class="text-primary d-inline-block">
              <Link href="/san-pham/dien-thoai/tat-ca"><i class="el-icon-caret-left"></i> Tiếp tục mua sắm</Link>
            </p>
          </div>
          {
            data &&
            <Table columns={this.columns} data={data} border={true} emptyText="Rỗng" maxHeight={500}></Table>
          }
          <p class="mt-30">
            <strong>Tổng cộng: </strong> <span class="text-danger fw-bold">{utils.formatMoney(total)}</span>
          </p>
          <p class="d-flex fl-x-center mt-30">
            <Button type="danger" className="width-100" onClick={e => this.payment(total)}>Thanh toán ngay</Button>
          </p>
        </Layout.Row>
      </div>
    )
  }
}