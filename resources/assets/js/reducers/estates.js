// default state
import {ADD_TO_CART, EDIT_CART, EMPTY_CART, REMOVE_FROM_CART, FILTER_ESTATES} from "../api/strings";

const estatesReducerDefaultState = [];

// reducer which is a pure function
export default (state = [], action) => {
    switch (action.type) {

        case FILTER_ESTATES:
            const estates = action.estates;
            return [
                ...estates
            ];
        case ADD_TO_CART:
            let idAlreadyExists = state.some(function (el) {
                return el.productID.toString() === action.shoppingCart.productID.toString();
            });
            if(idAlreadyExists){
                return state;
            }
            else{
                return [
                    ...state,
                    action.shoppingCart
                ];
            }
        case REMOVE_FROM_CART:
            return state.filter(({ productID }) => productID.toString() !== action.productID.toString());
        case EDIT_CART:
            return state.map((shoppingCart) => {
                if (shoppingCart.productID.toString() === action.productID.toString()) {
                    return {
                        ...shoppingCart,
                        ...action.updates
                    };
                } else {
                    return shoppingCart;
                }
            });
        case EMPTY_CART:
            return shoppingCartReducerDefaultState;
        default:
            return state;
    }
};