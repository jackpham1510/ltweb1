import { h, Component } from 'preact';
import { Router } from 'preact-router';

import utils from '../utils';
import config from '../../../config.json';

import Header from './Header';
import Footer from './Footer';

// Code-splitting is automated for routes
import Home from '../routes/Home';
import Product from '../routes/Product';

export default class App extends Component {
	/** Gets fired when the route changes.
	 *	@param {Object} event		"change" event from [preact-router](http://git.io/preact-router)
	 *	@param {string} event.url	The newly routed URL
	 */
	handleRoute = e => {
		this.currentUrl = e.url;
	};

	state = {
		categories: [],
		branchs: []
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
	}

	render() {
		const { categories, branchs } = this.state;
		return (
			<div id="app">
				<Header {...this.state} />
				{
					branchs && categories &&
					<div class="mt-20 mb-40">
						<Router onChange={this.handleRoute}>
							<Home path="/" {...this.state} />
							<Product path="/san-pham/:category/:branch?/:name?" {...this.state}></Product>
						</Router>
					</div>
				}
				<Footer></Footer>
			</div>
		);
	}
}
