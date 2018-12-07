import { h, Component } from 'preact';
import { Form, Input, Button, Notification } from 'element-react';
import { Link } from 'preact-router';
import utils from '../utils';
import authen from '../utils/authen';

export default class Login extends Component {
  state = {
    form: {
      username: '',
      password: ''
    },
    rules: {
      username: [
        { required: true, message: "Vui lòng điền tên đăng nhập", trigger: 'blur' },
      ],
      password: [
        { required: true, message: "Vui lòng điền mật khẩu", trigger: 'blur' }
      ]
    }
  }
  
  formRef = null;

  render(){
    const { form, rules } = this.state;
    return (
      <div class="container py-20">
        <Form ref={el => this.formRef = el} model={form} rules={rules} style={{ maxWidth: '500px', margin: 'auto' }}>
          <h2 align="center" class="text-primary">Đăng nhập</h2>
          <hr class="bd-0" style="border-top: 1px solid #20a0ff !important" />
          <Form.Item label="Tên đăng nhập" prop="username">
            <Input value={form.username} onChange={this.onChange.bind(this, 'username')}></Input>
          </Form.Item>
          <Form.Item label="Mật khẩu" prop="password">
            <Input type="password" value={form.password} onChange={this.onChange.bind(this, 'password')}></Input>
          </Form.Item>
          <Form.Item>
            <Button type="primary" className="width-100 mt-10" onClick={this.login}>Đăng nhập</Button>
            <p align="center">Bạn chưa có tài khoản? <Link href="/tai-khoan/dang-ky" class="text-primary">Đăng ký ngay</Link></p>
          </Form.Item>
        </Form>
      </div>
    )
  }
  onChange = (key, value) => {
    //console.log(key, value);
    this.setState({
      form: { ... this.state.form, [key]: value }
    });
  }
  login = e => {
    e.preventDefault();
    
    //console.log(this.formRef);
    console.log('submit');

    this.formRef.validate((valid) => {
      //console.log(valid);
      if (valid){
        utils.post('users/login', this.state.form, res => {
          if (res){
            authen.saveToken(res);
            window.open(window.origin + window.sessionStorage.getItem('last_url'), '_self');
          }
          else {
            Notification({
              type: 'error',
              title: 'Đăng nhập thất bại',
              message: `Đăng nhập thất bại, tên tài khoản hoặc mật khẩu không chính xác!`
            });
          }
        });
      }
    });
  }
}