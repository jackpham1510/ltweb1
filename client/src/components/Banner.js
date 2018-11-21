import { h, Component } from 'preact';
import { Link } from 'preact-router';
import { Layout, Carousel } from 'element-react';

import utils from '../utils';

export default class Banner extends Component{
  state = {
    big: null,
    small: null
  }
  constructor(props){
    super(props);
    utils.fetch('banner/all', data => {
      const big = data.filter(banner => banner['TYPE'] < 1000);
      const small = data.filter(banner => banner['TYPE'] > 1000);
      this.setState({ big, small });
    });
  }
  render(){
    const { big, small } = this.state;
    return (
      <Layout.Row>
        <Layout.Col span={16} xs={24} className="pr-10 banner-big">
          <Carousel className="bd-1 bdr-8" arrow="always">
          {
            big &&
            big.map(item => (
              <Carousel.Item>
                <Link href={`/san-pham${item['URL']}`}>
                  <img src={`../assets/images/banner/${item['PATH']}`} alt={item['PATH']} class="width-100" style="height: 300px;" />
                </Link>
              </Carousel.Item>
            ))
          }
          </Carousel>  
        </Layout.Col>
        <Layout.Col span={8} xs={24}>
          <Layout.Row className="pb-10">
          {
            small &&
            <Link href={`/san-pham${small[0]['URL']}`}>
              <img src={`../assets/images/banner/${small[0]['PATH']}`} alt={small[0]['PATH']} class="width-100 bd-1 bdr-8" style="height: 142px" />
            </Link>
          }
          </Layout.Row>
          <Layout.Row>
            {
              small &&
              <Link href={`/san-pham${small[1]['URL']}`}>            
                <img src={`../assets/images/banner/${small[1]['PATH']}`} alt={small[1]['PATH']} class="width-100 bd-1 bdr-8" style="height: 142px" />
              </Link>
            }
          </Layout.Row>
        </Layout.Col>
      </Layout.Row>
    )
  }
}