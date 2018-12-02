import { h, Component } from 'preact';
import { Link } from 'preact-router/match';

import { Menu, Input, Badge } from 'element-react';
import { route } from 'preact-router';
import authen from '../utils/authen';
import cart from '../utils/cart';

export default class Header extends Component{
	state = {
		menu: window.innerWidth >= 992,
		count: 0,
	}
	constructor(props){
		super(props);
		
	}
	componentWillReceiveProps(props) {
		if (props.isAuthen !== this.props.isAuthen && props.isAuthen){
			//console.log('authen');
			cart.subscribe('cart_count', count => this.setState({ count }));
			this.setState({
				count: cart.countItems(props.user['USERNAME'])
			});
		}
	}
	render(){
		const { categories, isAuthen, user } = this.props;
		const { menu, count } = this.state;

		const username = isAuthen && user['USERNAME'];
		//console.log('username', username);

		return (
			<div>
				<Menu theme="light" defaultActive="1" className="el-menu-demo container bg-white mb-5 header" mode="horizontal">
					<Menu.Item index="1" className="text-primary fw-bold fs-20 px-0 mr-10 bd-0 bg-white header-brand">
						<Link href="/" style="text-decoration: none !important; color: #20a0ff !important">
							<img src="../assets/logo.svg" width="64" alt="Logo" style="margin-left: -22px"/> DigiShop
						</Link>
						<i class="fa fa-navicon float-right mt-20" onClick={this.menuBtnClick}></i>
					</Menu.Item>
					<Menu.Item index="2" className="bg-white bd-0 px-0">
						<form onSubmit={this.search}>
							<Input icon="search" id="search" name="search" placeholder="Bạn cần gì?" className="search" />
						</form>
					</Menu.Item>
					<div className={`header-right float-right ${menu ? '' : 'd-none'}`}>
						<Menu.SubMenu className="mr-30" index="3" title={<span><i class="mr-10 fa fa-link" style="margin-top: -5px"></i>Liên hệ</span>}>
							{[
								['Facebook', 'facebook-square', 'https://fb.com/cpt.jack1998'],
								['LinkedIn', 'linkedin-square', 'https://www.linkedin.com/in/phamquantiendung/'],
								['Github', 'github', 'https://github.com/tiendung1510']
							].map((item, i) => (
									<a href={item[2]} class="text-dark" target="_blank">
										<Menu.Item index={`3-${i+1}`}><i className={`mr-10 fa fa-${item[1]}`}></i> {item[0]}</Menu.Item>
									</a>
							))}
						</Menu.SubMenu>
						<Menu.Item index="4" className="px-0 mr-30 bg-white">
							<Link href="/gio-hang">
							{
								count !== 0 ? 
								<Badge value={count}>
									<i class="fa fa-shopping-cart mr-10" style="margin-top: -5px"></i>Giỏ hàng
								</Badge> :
								<span class="d-inline-block" style="margin-top: -5px"><i class="fa fa-shopping-cart mr-10" style="margin-top: -5px"></i>Giỏ hàng</span>
							}
							</Link>
						</Menu.Item>
						{
							!username ? 
							<Menu.Item index="5" className="px-0 bg-white" index="5">
								<Link href="/dang-nhap"><i class="mr-10 fa fa-sign-in" style="margin-top: -5px"></i>Đăng nhập</Link>	
							</Menu.Item>
							:
							<Menu.SubMenu index="5" title={<span><i class="mr-10 fa fa-user" style="margin-top: -5px"></i>{username}</span>}>
								<Link href="/tai-khoan/cap-nhat">
									<Menu.Item index="5.1"><i class="mr-10 el-icon-edit" style="margin-top: -5px"></i>Cập nhật</Menu.Item>
								</Link>
								<Link href="/lich-su-mua-hang/1">
									<Menu.Item index="5.2"><i class="mr-10 fa fa-shopping-cart" style="margin-top: -5px"></i>Lịch sử mua hàng</Menu.Item>
								</Link>
								<Link onClick={e => authen.Logout()}>
									<Menu.Item index="5.3"><i class="mr-10 fa fa-sign-out" style="margin-top: -5px"></i>Đăng xuất</Menu.Item>
								</Link>
							</Menu.SubMenu>
						}
					</div>
				</Menu>
				<Menu theme="light" className={`bg-primary nav-menu container ${menu ? '' : 'd-none'}`} mode="horizontal">
					<div>
					{
						Object.keys(categories).map((k, idx) => (
							<Menu.Item index={idx + 1 + ''} className="nav-menu__item fs-16 px-0 bd-0 bg-primary mr-40">
								<Link href={`/san-pham/${categories[k]['URL']}/tat-ca`} activeClassName="active" class="d-inline-block bg-primary text-white" style="height: inherit"  onClick={this.menuBtnClick}>
									<img src={`../assets/images/icon/${categories[k]['URL']}-32.png`} alt={`${categories[k]['URL']}-icon`} class="mr-5" style="margin-top: -5px" />
									{categories[k]['NAME']}
								</Link>
							</Menu.Item>
						))
					}
					</div>
				</Menu>
			</div>
		)
	}
	menuBtnClick = e => {
		if (window.innerWidth < 992){
			this.setState({
				menu: !this.state.menu
			});
		}
	}
	search = e => {
		e.preventDefault();
		let $input = document.querySelector('#search');
		let input = $input.value;
		$input.value = "";
		route(`/tim-kiem?input=${input}&page=1`);
	}
}