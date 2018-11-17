import { h, Component } from 'preact';
import { Layout } from 'element-react';

import utils from '../utils';

import BranchList from '../components/BranchList';
import ProductList from '../components/ProductList';

export default class Product extends Component{
  constructor(props){
    super(props);
    console.log(props);
  }
  render(){
    return (
      <div class="">
        <BranchList></BranchList>
        <Layout.Row>
          <Layout.Col>
            {

            }
          </Layout.Col>
        </Layout.Row>
      </div>
    )
  }
}