import {LOG_IN, LOG_OUT} from "../api/strings";
import axios from 'axios';

const loginUserHelper = (authUser) => ({
    type: LOG_IN,
    authUser
});

export function loginUser2(username , password ){
    return (dispatch) => {
        axios.post('/api/admin/login/', username , password).then(function(response ){
            console.log(response.data);
            dispatch(loginUserHelper(response.data));
        });
    }
}

export const loginUser = () => ({
    type: LOG_IN
});
export const logoutUser = () => ({
    type: LOG_OUT
});