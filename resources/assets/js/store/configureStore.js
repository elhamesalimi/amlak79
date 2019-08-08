import { createStore, combineReducers, applyMiddleware } from 'redux';
import authenticationReducer from '../reducers/authentication';
import estates from '../reducers/estates';
import selectedCity from '../reducers/selectedcity';
import disabledSelectCity from '../reducers/selecteddisabledcity';
import thunk from 'redux-thunk';

const rootReducer = combineReducers({
    estates: estates,
    disabledSelectCity: disabledSelectCity,
    selectedCity: selectedCity,
    authentication: authenticationReducer,
});

export default  () => {
    const store = createStore(
        rootReducer,
        applyMiddleware(thunk)
    );

    return store;
};