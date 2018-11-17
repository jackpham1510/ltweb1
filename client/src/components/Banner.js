import { h } from 'preact';
import { Link } from 'preact-router';
import { Layout, Carousel } from 'element-react';

export default ({big, small}) => (
  <Layout.Row>
    <Layout.Col span={16} className="pr-10">
      <Carousel className="bd-1 bdr-8">
        {
          big.map(item => (
            <Carousel.Item>
              <Link href={item.url}>
                <img src={`../assets/images/${item.path}`} alt={item.path} class="width-100" style="height: 300px;" />
              </Link>
            </Carousel.Item>
          ))
        }
      </Carousel>  
    </Layout.Col>
    <Layout.Col span={8}>
      <Layout.Row className="pb-10">
        <img src={`../assets/images/${small[0].path}`} alt={small[0].path} class="width-100 bd-1 bdr-8" style="height: 142px" />
      </Layout.Row>
      <Layout.Row>
      <img src={`../assets/images/${small[1].path}`} alt={small[1].path} class="width-100 bd-1 bdr-8" style="height: 142px" />
      </Layout.Row>
    </Layout.Col>
  </Layout.Row>
)