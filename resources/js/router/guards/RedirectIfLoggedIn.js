import { store } from '../../store';
import { i18n } from '../../plugins/i18n.js';

export default (to, from, next) => {
	if (store.state.auth.loggedin) {
		if(store.state.auth.user.role.name === 'admin') {
			next({name: 'admin.index'});
		}else {
			next({name: 'dashboard.index'});
		}
		
	} else {
		next();
	}
}