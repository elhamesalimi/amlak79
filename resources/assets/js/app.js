import 'core-js/es/map';
import 'core-js/es/set';
import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import AppRouter from './routers/AppRouter';
import configureStore from './store/configureStore';
import axios from "axios";
import {loginUser, logoutUser} from "./actions/authentication";
import {ACCESS_TOKEN} from "./api/strings";
import {REFRESH_TOKEN} from "./api/strings";
import {filterEstates} from "./actions/estates";

const store = configureStore();

const App = () => (
        <AppRouter />
);

const jsx = (
    <Provider store={store}>
        <App />
    </Provider>
);

// const product = {
//     productName: "Product Name",
//     productImage: imageWatch,
//     sellerName: "Seller Name",
//     quantity: 1,
//     price: 19.99,
//     productID: 1
// };
console.log('app render');
window.localStorage.removeItem('advancedFilter');
// initial load, check if user is logged in
const access_token = window.localStorage.getItem(ACCESS_TOKEN);
const headers = {Accept: "application/json", Authorization: `Bearer ${access_token}`};
axios.get('/api/getUserAdvertisment', {headers})
    .then((response) => {
        store.dispatch(loginUser());
        // response.data.map((item) => {
        //     const productName = item.name;
        //     const productImage = item.image;
        //     const sellerName = item.sellerName;
        //     const ratings = item.ratings;
        //     const quantity = 1;
        //     const price = item.price;
        //     const productID = item.productId;
        //     const product = {
        //         productName,
        //         productImage,
        //         sellerName,
        //         ratings,
        //         quantity,
        //         price,
        //         productID
        //     };
        //     store.dispatch(addToCartHelper(product));
        // })
    })
    .catch((error) => {
        window.localStorage.removeItem(ACCESS_TOKEN);
        window.localStorage.removeItem(REFRESH_TOKEN);
        store.dispatch(logoutUser());
    });
store.dispatch(filterEstates());
const appRoot = document.getElementById('app');
ReactDOM.render(jsx, appRoot);