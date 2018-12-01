import { h, Component } from 'preact';
import { Layout, Table, Pagination, Button } from 'element-react';
import { Link, route } from 'preact-router';
import config from '../../../config.json';

import utils from '../utils';
import { OrderDetail } from '../components/OrderDetail.js';

export default class BuyHistory extends Component {
  state = {
    data: [],
    pagination: null,
    detail: null
  }

  constructor(props){
    super(props);
    this.fetchData(props);
  }

  componentWillReceiveProps(props) {
    this.fetchData(props);
  }

  fetchData = props => {
    utils.fetch(`orders/list?username=${props.user['USERNAME']}&page=${props.page}`, res => {
      if (res && res.total !== 0){
        this.setState({
          data: res.data,
          pagination: {
            currentPage: res.active,
            total: parseInt(res.total)
          } 
        })
      }
    });
  }

  showDetail = order_id => {
    utils.fetch(`orders/detail?order_id=${order_id}`, items => {
      console.log(items);
      this.setState({
        detail: items.map(item => ({ ...item, DETAIL: JSON.parse(item['DETAIL']) }))
      });
    });
  }

  closeDetail = () => {
    this.setState({
      detail: null
    })
  }

  columns = [
    { 
      label: 'Mã đơn hàng', prop: 'ORDER_ID', align: 'center'
    },
    { 
      label: 'Ngày đặt', prop: 'ORDER_DATE', align: 'center', sortable: true
    },
    { 
      label: 'Ngày nhận', prop: 'RECIVE_DATE', align: 'center', sortable: true
    },
    { 
      label: 'Trạng thái', prop: 'STATUS', align: 'center', sortable: true,
      render: item => (
        <i class={"text-"+(item['STATUS'] >= 0 ? 'success' : 'danger')}>{config['order_status'][item['STATUS']]}</i>
      )
    },
    { 
      label: 'Thành tiền', prop: 'PRICE',  align: 'center', sortable: true,
      render: item => (
        <i class="text-danger fw-bold">{utils.formatMoney(item['PRICE'])}</i>
      )
    },
    {
      label: 'Chi tiết', align: 'center',
      render: item => (
        <Button type="primary" size="small" onClick={e => this.showDetail(item['ORDER_ID'])}>Chi tiết</Button>
      )
    }
  ]

  render(){
    let { data, pagination, detail } = this.state;
    
    return (
      <div class="container">
        <Layout.Row>
          {
            detail &&
            <OrderDetail branchs={this.props.branchs} categories={this.props.categories} close={this.closeDetail} items={detail}></OrderDetail>
          }
          <h2 align="center" class="text-primary">Lịch sử mua hàng</h2>
          <hr class="bd-0" style="border-top: 1px solid #20a0ff !important" />
          <div>
            <p class="text-primary d-inline-block">
              <Link href="/san-pham/dien-thoai/tat-ca"><i class="el-icon-caret-left"></i> Tiếp tục mua sắm</Link>
            </p>
          </div>
          {
            data &&
            <Table columns={this.columns} data={data} border={true} emptyText="Rỗng"></Table>
          }
          <p class="d-flex fl-x-center mt-30">
          {
            pagination &&
            <Pagination layout="prev, pager, next" {...pagination} pageSize={10} onCurrentChange={this.changePage}/>
          }
          </p>
        </Layout.Row>
      </div>
    )
  }

  changePage = page => {
    //console.log(this.props.url, page);
    route(`/lich-su-mua-hang/`+page);
    //this.fetchData()
  }
}