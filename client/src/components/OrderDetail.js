import { h, Component } from 'preact';
import { Layout, Table, Dialog } from 'element-react';
import { Link } from 'preact-router';
import utils from '../utils';

export class OrderDetail extends Component {
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
    { label: 'Số lượng', prop: 'quantity', sortable: true, align: 'center', width: 130 },
    { 
      label: 'Giá', prop: 'price', align: 'center', sortable: true, width: 200,
      render: item => (
        <span class="text-danger fw-bold">{utils.formatMoney(item['PRICE'])}</span>
      )
    },   
  ]

  render(){
    let { items, close } = this.props;
    let total = utils.totalPrice(items);
    
    return (
      <div class="container">
        <Dialog
          title="  "
          visible={ true }
          onCancel={ close }
        >
          <Dialog.Body>
            <h2 align="center" class="text-primary">Chi tiết đơn hàng</h2>
            <hr class="bd-0 mb-30" style="border-top: 1px solid #20a0ff !important" />
            {
              items &&
              <Table columns={this.columns} data={items} border={true} emptyText="Rỗng" maxHeight={500}></Table>
            }
            <p class="mt-30">
              <strong>Tổng cộng: </strong> <span class="text-danger fw-bold">{utils.formatMoney(total)}</span>
            </p>
          </Dialog.Body>
        </Dialog>
      </div>
    )
  }
}