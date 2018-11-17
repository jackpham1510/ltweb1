import { h, Component } from 'preact';
import { Link } from 'preact-router/match';

import { Menu, Input } from 'element-react';

export default () => (
	<div>
		<Menu theme="light" defaultActive="1" className="el-menu-demo container bg-white mb-5 header" mode="horizontal">
			<Menu.Item index="1" className="text-primary fw-bold fs-20 px-0 mr-10 bd-0 bg-white header-brand">
				<img src="../assets/logo.svg" width="64" alt="Logo" style="margin-left: -22px"/> DigiShop
				<i class="fa fa-navicon float-right mt-20"></i>
			</Menu.Item>
			<Menu.Item index="2" className="bg-white bd-0 px-0"><Input icon="search" placeholder="Bạn cần gì?" className="search" /></Menu.Item>
			<div className="header-right float-right">
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
		<Menu theme="light" className="bg-primary nav-menu container" mode="horizontal">
			<div>
				<Menu.Item index="1" className="nav-menu__item fs-16 px-0 bd-0 bg-primary mr-40">
					<Link href="/dien-thoai" activeClassName="active" class="d-inline-block bg-primary text-white" style="height: inherit"><i class="fa fa-mobile fs-28 mr-10"></i> Điện thoại</Link>
				</Menu.Item>
				<Menu.Item index="2" className="nav-menu__item fs-16 px-0 bd-0 bg-primary mr-40">
					<Link href="/tablet" activeClassName="active" class="d-inline-block bg-primary bd-0 text-white" style="height: inherit"><i class="fa fa-tablet fs-23 mr-10"></i>Máy tính bảng</Link>
				</Menu.Item>
				<Menu.Item index="3" className="nav-menu__item fs-16 px-0 bd-0 mr-40 bg-primary ">
					<Link href="/tai-nghe" activeClassName="active" class="d-inline-block text-white bg-primary" style="height: inherit"><i class="fa fa-headphones fs-20 mr-10"></i>Tai nghe</Link>
				</Menu.Item>
				<Menu.Item index="4" className="nav-menu__item fs-16 px-0 bd-0 mr-40 bg-primary">
					<Link href="/pin-sac-du-phong" activeClassName="active" class="d-inline-block text-white bg-primary" style="height: inherit"><i class="fa fa-battery-full fs-20 mr-10"></i>Pin, sạc dự phòng</Link>
				</Menu.Item>
				<Menu.Item index="5" className="nav-menu__item fs-16 px-0 bd-0 mr-40 bg-primary">
					<Link href="/cable" activeClassName="active" class="d-inline-block text-white bg-primary" style="height: inherit"><i class="fa fa-plug fs-20 mr-10"></i>Dây cáp</Link>
				</Menu.Item>
				<Menu.Item index="6" className="nav-menu__item fs-16 px-0 bd-0 mr-40 bg-primary">
					<Link href="/the-nho" activeClassName="active" class="d-inline-block text-white bg-primary" style="height: inherit"><i class="fa fa-hdd-o fs-20 mr-10"></i>Thẻ nhớ</Link>
				</Menu.Item>
			</div>
		</Menu>
	</div>
)