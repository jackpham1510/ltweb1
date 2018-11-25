import { h, Component } from 'preact';
import { Form, Input, Button } from 'element-react';
import { Link } from 'preact-router';
import utils from '../utils';
import config from '../../../config.json';

export default class Register extends Component {
  state = {
    form: {
      username: '',
      password: '',
      repassword: '',
      name: '',
      phone: '',
      address: ''
    },
    rules: {
      username: [
        { required: true, message: 'Vui lòng điền tên đăng nhập', trigger: 'blur' },
        { validator: (rule, value, callback) => {
            if (value.length < 4){
              callback(new Error('Tên đăng nhập phải có ít nhất 4 kí tự'));
            } 
            else if (value.match(/\W+/)) {
              callback(new Error('Tên đăng nhập không được chứa kí tự đặc biệt'))
            } 
            callback();
        } },
        { validator: (rule, value, callback) => {
          if (value.length >= 4){
            setTimeout(function (){
              utils.fetch(`users/check?name=${value}`, function (rs){
                //console.log(rs);
                if (rs){
                  callback(new Error('Tên đăng nhập đã tồn tại'));
                }
                callback();
              });
            }, 500);
          }
        }, trigger: 'blur' }
      ],
      password: [
        { required: true, message: 'Vui lòng điền mật khẩu', trigger: 'blur' },
        { validator: (rule, value, callback) => {
            if (value.length < 6){
              callback(new Error('Mật khẩu phải có ít nhất 6 kí tự'));
            }
            else if (value.match(/\W+/)) {
              callback(new Error('Mật khẩu không được chứa kí tự đặc biệt'))
            } 
            callback();
        } }
      ],
      repassword: [
        { required: true, message: 'Vui lòng xác nhận lại mật khẩu', trigger: 'blur' },
        { validator: (rule, value, callback) => {
            if (value !== this.state.form.password){
              callback(new Error('Xác nhận mật khẩu không chính xác'));
            }
            callback();
        } }
      ],
      name: [
        { required: true, message: 'Vui lòng điền họ tên của bạn', trigger: 'blur' }
      ],
      phone: [
        { required: true, message: 'Vui lòng điền số điện thoại', trigger: 'blur' },
        { validator: (rule, value, callback) => {
            let phone = parseInt(value);
            if (!Number.isInteger(phone) || value.length < 9){
              callback(new Error('Số điện thoại không hợp lệ'));
            }
            callback();
        } }
      ],
      address: [
        { required: true, message: 'Vui lòng điền địa chỉ', trigger: 'blur' },
      ]
    }
  }
  
  formRef = null;

  render(){
    const { form, rules } = this.state;
    return (
      <div class="container pt-20">
        <Form ref={el => this.formRef = el} model={form} rules={rules} style={{ maxWidth: '500px', margin: 'auto' }}>
          <h2 align="center" class="text-primary">Đăng ký</h2>
          <Form.Item label="Tên tài khoản" prop="username">
            <Input value={this.state.form.username} onChange={this.onChange.bind(this, 'username')}></Input>
          </Form.Item>
          <Form.Item label="Mật khẩu" prop="password">
            <Input type="password" value={this.state.form.password} onChange={this.onChange.bind(this, 'password')}></Input>
          </Form.Item>
          <Form.Item label="Xác nhận mật khẩu" prop="repassword">
            <Input type="password" value={this.state.form.repassword} onChange={this.onChange.bind(this, 'repassword')}></Input>
          </Form.Item>
          <Form.Item label="Họ tên của bạn" prop="name">
            <Input value={this.state.form.name} onChange={this.onChange.bind(this, 'name')}></Input>
          </Form.Item>
          <Form.Item label="Điện thoại" prop="phone">
            <Input value={this.state.form.phone} onChange={this.onChange.bind(this, 'phone')}></Input>
          </Form.Item>
          <Form.Item label="Địa chỉ" prop="address">
            <Input type="textarea" autosize={{ minRows: 3}} value={this.state.form.address} onChange={this.onChange.bind(this, 'address')}></Input>
          </Form.Item>
          <Form.Item className="mt-30">
            <Button type="primary" className="width-100" onClick={this.handleSubmit}>Đăng ký</Button>
            <p align="center">Hoặc quay lại <Link href="/dang-nhap" class="text-primary">trang đăng nhập</Link></p>
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
  handleSubmit = (e) => {
    e.preventDefault();

    this.formRef.validate(async (valid) => {
      if (valid){
        let res = await fetch(`${config.serverhost}/users/add`, {
          method: 'post',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(this.state.form)
        });
        let data = await res.text();
        //console.log(data);
        if (data){
          console.log(data);
        }
      }
    });
  }
}