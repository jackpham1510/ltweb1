import App from './components/App';
import 'element-theme-default';

import './style/index.css';
import './style/compiled/main.min.css';

document.head.innerHTML = '<base href="/" />' + document.head.innerHTML;

export default App;
