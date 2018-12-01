import { h, Component } from 'preact';
import utils from '../utils';
import { Button, Dialog, Table } from 'element-react';
import { Link } from 'preact-router';
import cart from '../utils/cart';

export class BuyDialog extends Component{
  state = {
    status: 0,
    orderID: 123,
    outOfStock: [],
    items: []
  }

  buy = total => {
    let username = this.props.user['USERNAME'];
    let order = { username, items: this.props.items, total }

    utils.post('orders/insert', { order }, res => {  
      if (res.success){
        if (this.props.clearCart){
          cart.clear(username);
        }

        this.setState({
          status: 1,
          orderID: res.orderID
        });
      }
      else {
        this.setState({
          status: -1,
          outOfStock: res.outOfStock
        })
      }
    })
  }

  close = () => {
    this.setState({
      status: 0,
    });
    this.props.close();
  }

  columns = [
    { label: 'Sản phẩm không đủ hàng', prop: "name", align: "center" }
  ]

  render(){
    let { user, items } = this.props;

    if (!items) return null;

    let { status, orderID, outOfStock } = this.state;
    let total = items ? utils.totalPrice(items) : 0;
    
    return (
        <Dialog
          title=""
          visible={ true }
          onCancel={this.close}
          style={{width: 420, maxWidth: '100%'}}
        >
          <Dialog.Body>
          {
            status === 0 ?
            <div>
              <h2 align="center" class="text-primary">Hóa đơn thanh toán</h2>
              <hr class="bd-0" style="border-top: 1px solid #20a0ff !important" />
              <p class="my-5"><b>Họ tên khách hàng:</b> {user['NAME']}</p>
              <p class="my-5"><b>Số điện thoại:</b> {user['PHONE']}</p>
              <p class="my-5"><b>Địa chỉ:</b> {user['ADDRESS']}</p>
              <hr class="bd-0 mt-20" style="border-top: 1px solid #20a0ff !important" />
              {
                items.map(item => (
                  <div class="my-5">
                    <span class="float-right ml-20"><b class="text-danger">{utils.formatMoney(item['PRICE'])}</b> * {item.quantity}</span>
                    <span>{item['NAME']}</span>
                  </div>
                ))
              }
              <hr class="bd-0 mt-20" style="border-top: 1px solid #20a0ff !important" />
              <div>Tổng cộng: <b class="text-danger float-right">{utils.formatMoney(total)}</b></div>
              <Button type="primary" className="mt-40 width-100" onClick={e => this.buy(total)}>Thanh toán</Button>
            </div> :
            <div class="pb-20">
              <h2 align="center" class="text-primary">Kết quả thanh toán</h2>
              <hr class="bd-0" style="border-top: 1px solid #20a0ff !important" />
              <div class="d-flex fl-x-center fl-y-center" style="flex-direction: column">
                <i style="font-size: 100px;" class={"mt-20 mb-10 el-icon-circle-"+(status === 1 ? 'check text-success' : 'cross text-danger')}></i>
                <h2 align="center" class={"mt-10 mb-5 "+(status === 1 ? 'text-success' : 'text-danger')}>
                  <i>{status === 1 ? 'Thanh toán thành công,' : 'Thanh toán thất bại'}</i><br/>
                  <i>{status === 1 ? 'Xin cảm ơn quý khách đã mua hàng!' : 'Xin quý khách vui lòng thử lại!'}</i>
                </h2>
                {
                  status === 1 ?
                  <div align="center">
                    <small>Mã đơn hàng: {orderID}</small><br/>
                    <small class="text-primary">
                      <Link href="/lich-su-mua-hang">Xem lịch sử giao hàng tại đây</Link>
                    </small>
                  </div> :
                  (
                    outOfStock && outOfStock.length > 0 &&
                    <div class="width-100 mt-30">
                      <Table data={outOfStock} columns={this.columns} style={{maxHeight: 200, overflow: 'auto'}}></Table>
                    </div>
                  )
                }
              </div>
            </div>
          }
          </Dialog.Body>
        </Dialog>
    )
  }
}