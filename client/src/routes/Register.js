import { h, Component } from 'preact';
import { Form, Input, Button, Notification } from 'element-react';
import { Link } from 'preact-router';
import ReCaptcha from 'preact-google-recaptcha';
import utils from '../utils';
import authen from '../utils/authen';

export default class Register extends Component {
  state = {
    verify: 0,
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
          if (this.props.type !== 'cap-nhat' && value.length >= 4){
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
          callback();
        }, trigger: 'blur' }
      ],
      password: [
        { required: this.props.type !== 'cap-nhat', message: 'Vui lòng điền mật khẩu', trigger: 'blur' },
        { validator: (rule, value, callback) => {
            if (this.props.type === 'cap-nhat' && !value){
              callback();
              return;
            }
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
        { required: this.props.type !== 'cap-nhat', message: 'Vui lòng xác nhận lại mật khẩu', trigger: 'blur' },
        { validator: (rule, value, callback) => {
            if (this.props.type === 'cap-nhat' && !value){
              callback();
              return;
            }
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

  constructor(props){
    super(props);

    if (props.type === 'cap-nhat'){
      let { user } = props;
      this.setState({
        form: {
          username: user['USERNAME'],
          name: user['NAME'],
          phone: user['PHONE'],
          address: user['ADDRESS']
        }
      });
    }
  }

  grecaptcha = null

  verifyGrecaptcha = gres => {
    utils.fetch('grecaptcha/verify?gres='+gres, res => {
      if (!res){
        this.setState({ verify: -1 });
        this.grecaptcha.reset();
      }
      else {
        this.setState({ verify: 1 });
      }
    });
  }

  onExpiredCaptcha = () => {
    this.setState({ verify: -1 });
  }

  render(){
    const { form, rules, verify } = this.state;
    const isUpdate = this.props.type === 'cap-nhat';

    return (
      <div class="container pt-20">
        <Form ref={el => this.formRef = el} model={form} rules={rules} style={{ maxWidth: '500px', margin: 'auto' }}>
          <h2 align="center" class="text-primary">{isUpdate ? 'Cập nhật tài khoản' : 'Đăng ký'}</h2>
          <hr class="bd-0" style="border-top: 1px solid #20a0ff !important" />
          <Form.Item label="Tên tài khoản" prop="username">
            <Input value={this.state.form.username} onChange={this.onChange.bind(this, 'username')} disabled={isUpdate}></Input>
          </Form.Item>
          <Form.Item label={isUpdate ? "Mật khẩu mới" : "Mật khẩu"} prop="password">
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
          <Form.Item className="d-flex fl-x-center pt-10">
            <ReCaptcha ref={el => this.grecaptcha = el} 
              sitekey="6LfkP34UAAAAANY2wOoxIQ6QagCbpmue6zTr05bv" 
              onChange={this.verifyGrecaptcha}
              onExpired={this.onExpiredCaptcha} />
            {
              verify === -1 &&
              <p class="my-0" style="color: #ff4949" align="center">Lỗi xác thực, bạn vui lòng xác thực lại!</p>
            }
            {
              verify === -2 &&
              <p class="my-0" style="color: #ff4949" align="center">Bạn vui lòng xác thực trước khi đăng ký!</p>
            }
          </Form.Item>
          <Form.Item className="mt-30">
            <Button type="primary" className="width-100" onClick={this.handleSubmit}>{isUpdate ? 'Cập nhật' : 'Đăng ký'}</Button>
            {
              !isUpdate && <p align="center">Hoặc quay lại <Link href="/dang-nhap" class="text-primary">trang đăng nhập</Link></p>
            }
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
    let { verify } = this.state;
    this.formRef.validate((valid) => {
      if (verify === 0){
        this.setState({ verify: -2 });
      }
      if (valid && verify === 1){
        const isUpdate = this.props.type === 'cap-nhat';
        console.log(this.state.form);
        utils.post(isUpdate ? 'users/update' : 'users/add', this.state.form, res => {
          if (res){
            if (isUpdate){
              Notification({
                type: 'success',
                title: 'Thông báo',
                message: `Cập nhật tài khoản thành công!`
              });
            }
            else {
              authen.saveToken(res);
              window.location.pathname = "/"
            }
          }
          else {
            Notification({
              type: 'danger',
              title: 'Thông báo',
              message: `${isUpdate ? 'Cập nhật' : 'Đăng ký'} tài khoản thất bại, vui lòng tải lại trang và thử lại!`
            });
          }
        });
      }
    });
  }
}