import { h, Component } from 'preact';
import { Layout, Table, Button, Notification, MessageBox } from 'element-react';
import { Link } from 'preact-router';

import cart from '../utils/cart';
import utils from '../utils';

export default class Cart extends Component {
  state = {
    data: []
  }
  constructor(props){
    super(props);
    this.updateCart();
    cart.subscribe('cart_update', this.updateCart);
  }
  updateCart = () => {
    let items = cart.getItems(this.props.user['USERNAME']);
    let data = [];
    for (let id in items){
      data.push({...items[id], id });
    }
    this.setState({ data });
  }
  columns = [
    { 
      label: 'Hình ảnh', prop: 'image', align: 'center', width: 150,
      render: (item) => (
        <img width="64" src={`../assets/images/details/${item.category}/${item.branch}/${item.image}`} alt={item.image}/>
      )  
    },
    { 
      label: 'Tên sản phẩm', prop: 'productName', align: 'center',
      render: item => (
        <Link href={`/san-pham/${item.category}/${item.branch}/${item.url}`} class="text-primary">{item.productName}</Link>
      )
    },
    { label: 'Màu', prop: 'color', align: 'center', width: 100 },
    { 
      label: 'Giá', prop: 'price', align: 'center', sortable: true, width: 200,
      render: item => (
        <span class="text-danger fw-bold">{utils.formatMoney(item.price)}</span>
      )
    },
    { label: 'Số lượng', prop: 'quantity', sortable: true, align: 'center', width: 130 },
    { 
      label: 'Xóa', prop: 'delete', align: 'center', width: 150,
      render: item => (
        <Button type="danger" icon="delete" onClick={e => this.removeItem(item.id)}>Xóa</Button>
      ) 
    }
  ]
  clearCart = e => {
    if (this.state.data.length > 0){
      cart.clear(this.props.user['USERNAME']);
      this.setState({
        data: []
      });
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
    
  }
  render(){
    let { data } = this.state;
    let total = data.reduce((p, c) => (p + c.price * c.quantity), 0);
    return (
      <div class="container">
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