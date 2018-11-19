import { h, Component } from 'preact';
import { Link } from 'preact-router/match';

import { Menu, Input } from 'element-react';

export default class Header extends Component{
	state = {
		menu: window.innerWidth >= 992
	}
	menuBtnClick = (e) => {
		if (window.innerWidth < 992){
			this.setState({
				menu: !this.state.menu
			});
		}
	}
	render(){
		const { categories } = this.props;
		const { menu } = this.state;
		return (
			<div>
				<Menu theme="light" defaultActive="1" className="el-menu-demo container bg-white mb-5 header" mode="horizontal">
					<Menu.Item index="1" className="text-primary fw-bold fs-20 px-0 mr-10 bd-0 bg-white header-brand">
						<Link href="/" style="text-decoration: none !important">
							<img src="../assets/logo.svg" width="64" alt="Logo" style="margin-left: -22px"/> DigiShop
						</Link>
						<i class="fa fa-navicon float-right mt-20" onClick={this.menuBtnClick}></i>
					</Menu.Item>
					<Menu.Item index="2" className="bg-white bd-0 px-0"><Input icon="search" placeholder="Bạn cần gì?" className="search" /></Menu.Item>
					<div className={`header-right float-right ${menu ? '' : 'd-none'}`}>
						<Menu.SubMenu index="3" title="Liên hệ" className="mr-30">
							{[
								['Facebook', 'facebook-square', 'https://fb.com/cpt.jack1998'],
								['LinkedIn', 'linkedin-square', 'https://www.linkedin.com/in/phamquantiendung/'],
								['Github', 'github', 'https://github.com/tiendung1510']
							].map((item, i) => (
								<Menu.Item index={`3-${i+1}`}>
									<a href={item[2]} class="text-dark" target="_blank"><i className={`mr-10 fa fa-${item[1]}`}></i> {item[0]}</a>
								</Menu.Item>
							))}
						</Menu.SubMenu>
						<Menu.Item index="4" className="px-0 mr-30 bg-white">Giỏ hàng</Menu.Item>
						<Menu.Item index="5" className="px-0 mr-30 bg-white">Tài khoản</Menu.Item>
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
}