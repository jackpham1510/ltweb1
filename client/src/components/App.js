import { h, Component } from 'preact';
import { Router, route } from 'preact-router';
import { Notification } from 'element-react';

import utils from '../utils';
import config from '../../../config.json';

import Header from './Header';
import Footer from './Footer';

// Code-splitting is automated for routes
import Home from '../routes/Home';
import Product from '../routes/Product';
import ProductDetail from '../routes/ProductDetail';
import SearchResult from '../routes/SearchResult';
import Login from '../routes/Login';
import Register from '../routes/Register';
import authen from '../utils/authen';
import Cart from '../routes/Cart';
import BuyHistory from '../routes/BuyHistory';

export default class App extends Component {
	state = {
		categories: [],
		branchs: [],
		user: null,
		isAuthen: false
	};

	constructor(props){
		super(props);
		fetch(`${config.serverhost}/branch/all`)
			.then(res => res.json())
			.then(branchs => this.setState({ 
				branchs: utils.flat(branchs, 'BRANCH_ID') 
			}));
		
		fetch(`${config.serverhost}/category/all`)
			.then(res => res.json())
			.then(categories => this.setState({
				categories: utils.flat(categories, 'CATEGORY_ID')
			}));
		
		(async () => {
			let isAuthen = await authen.isAuthenticated();
			if (isAuthen){
				this.setState({
					user: authen.user,
					isAuthen: isAuthen
				}, () => {
					Notification({
						type: 'success',
						title: 'Lời nhắn',
						message: `Xin chào ${this.state.user['NAME']}!`
					});
				});
			}
		})();
	}

	handleRoute = async e => {
		let mustAuthen = config['must_authen_list'].includes(e.url);
		let mustNotAuthen = config['must_not_authen_list'].includes(e.url);
		
		if (mustAuthen || mustNotAuthen){
			let isAuthen = await authen.isAuthenticated();

			if (mustAuthen && !isAuthen){
				route('/dang-nhap');
			}
			else if (mustNotAuthen && isAuthen){
				route('/');
			}
		}
		else {
			window.sessionStorage.setItem('last_url', e.url);
		}
	}

	render() {
		const { categories, branchs, user } = this.state;
		return (
			<div id="app">
				<Header {...this.state} />
				{
					branchs && categories && user &&
					<div class="mt-20 mb-40">
						<Router onChange={this.handleRoute}>
							<Home path="/" {...this.state} />
							<Product path="/san-pham/:category/:branch" {...this.state}></Product>
							<ProductDetail path="/san-pham/:category/:branch/:productUrl" {...this.state}></ProductDetail>
							<SearchResult path="/tim-kiem" {...this.state}></SearchResult>
							<Login path="/dang-nhap"></Login>
							<Register path="/tai-khoan/:type" user={user}></Register>
							<Cart path="/gio-hang" {...this.state}></Cart>
							<BuyHistory path="/lich-su-mua-hang/:page" {...this.state}></BuyHistory>
						</Router>
					</div>
				}
				<Footer></Footer>
			</div>
		);
	}
}
