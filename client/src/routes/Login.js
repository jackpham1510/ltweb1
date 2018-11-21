import { h, Component } from 'preact';
import { Form, Input, Button } from 'element-react';
import { Link } from 'preact-router';

export default class Login extends Component {
  rules = {
    username: [
      { required: true, message: "Vui lòng điền tên đăng nhập", trigger: 'blur' },
    ],
    password: [
      { required: true, message: "Vui lòng điền mật khẩu", trigger: 'blur' }
    ]
  }
  state = {
    form: {
      username: '',
      password: ''
    }
  }
  render(){
    const { form } = this.state;
    return (
      <div class="container py-20">
        <Form model={form} rules={this.rules} required={true} style={{ maxWidth: '500px', margin: 'auto' }}>
          <h2 align="center" class="text-primary">Đăng nhập</h2>
          <Form.Item label="Tên đăng nhập" prop="username">
            <Input value={form.username}></Input>
          </Form.Item>
          <Form.Item label="Mật khẩu" prop="password" required={true}>
            <Input type="password" value={form.password}></Input>
          </Form.Item>
          <Form.Item>
            <Button type="primary" className="width-100 mt-10">Đăng nhập</Button>
            <p align="center">Bạn chưa có tài khoản? <Link href="/dang-ky" class="text-primary">Đăng ký ngay</Link></p>
          </Form.Item>
        </Form>
      </div>
    )
  }
}