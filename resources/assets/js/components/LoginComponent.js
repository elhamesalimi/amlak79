import React from 'react';
import { FormGroup, ControlLabel, FormControl, HelpBlock, Button, Grid, Row, Col } from 'react-bootstrap';
import {withRouter, Link} from 'react-router-dom';
import axios from 'axios';
import {loginAPI, getUserAPI, getUserCartAPI} from "../api/apiURLs";
import {loginUser, logoutUser} from "../actions/authentication";
import { connect } from 'react-redux';
import {ACCESS_TOKEN, REFRESH_TOKEN} from "../api/strings";
import LoadingScreen from "../components/LoadingScreen";

const FieldGroup = ({ id, label, help, ...props }) => (
        <FormGroup controlId={id}>
            <ControlLabel>{label}</ControlLabel>
            <FormControl {...props} />
            {help && <HelpBlock>{help}</HelpBlock>}
        </FormGroup>
);

class LoginComponent extends React.Component{

    state = {
        passwordHelp: undefined,
        usernameHelp: undefined,
        invalidCredentials: undefined,
        isLoading: false
    };

    loadCartService = () => {
        const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        const headers = {Accept: "application/json", Authorization: `Bearer ${access_token}`};
        console.log('headers',headers);
        axios.get(getUserAPI, {headers})
            .then((response) => {
                console.log('getUserApi :',response);
                    this.props.dispatch(loginUser());
                    this.props.history.push("/");
                }
            )
            .catch((error) => {
                console.log(error.response);
                window.localStorage.removeItem(ACCESS_TOKEN);
                window.localStorage.removeItem(REFRESH_TOKEN);
                this.props.dispatch(logoutUser());
            });
    };
re
    componentDidMount(){
        if(window.localStorage.getItem(ACCESS_TOKEN) !== null){
            // means the user is already logged in, check if it is valid
            this.setState(() => ({isLoading: true}));
            this.loadCartService();
        }
    }

    onLoginSubmit = (e) => {
        e.preventDefault();
        const email = e.target.formControlsUsername.value;
        const password = e.target.formControlsPassword.value;

        if(password.length === 0){
            this.setState(() => ({passwordHelp: "رمز عبور رو وارد نمایید"}));
        }else{
            this.setState(() => ({passwordHelp: undefined}));
        }

        if(email.length === 0){
            this.setState(() => ({usernameHelp: "نام کاربری را وارد نمایید"}));
        }else{
            this.setState(() => ({usernameHelp: undefined}));
        }
console.log('email : ',email , 'password : ',password);
        if(email.length > 0 && password.length > 0){
            this.setState(() => ({isLoading: true}));
            const data = {
              grant_type: "password",
              client_id: "2",
              client_secret: window.Laravel.client_secret,
              username: email,
              password: password,
              scope: "*",
                guard:"admin-api"
            };

            axios.post(loginAPI, data)
                .then((response) => {
                    window.localStorage.setItem('access_token', response.data.access_token);
                    window.localStorage.setItem('refresh_token', response.data.refresh_token);
                    this.props.dispatch(loginUser());
                    this.loadCartService();
                })
                .catch((error) => (
                    this.setState(() => ({
                        invalidCredentials: true,
                        isLoading: false
                    }))
                ));
        }
    };

    render(){
        if(this.state.isLoading){
            return <LoadingScreen/>
        }

        return (
            <Grid className={"minimum-height"}>
                <Row>
                    <Col mdOffset={3} lgOffset={3} lg={6} md={6}>
                        <h3 className={"text-center"}>ورود</h3>
                        <form onSubmit={this.onLoginSubmit}>
                            <FieldGroup
                                id="formControlsUsername"
                                type="text"
                                label=" نام کاربری "
                                placeholder="نام کاربری خود را وارد نمایید"
                                help={this.state.usernameHelp}
                            />
                            <FieldGroup
                                id="formControlsPassword"
                                label="رمز عبور"
                                type="password"
                                placeholder="رمز عبور خود را وارد نمایید"
                                help={this.state.passwordHelp}
                            />
                            {this.state.invalidCredentials && <p className={"error"}>رمز عبور یا نام کاربری صحیح نمی باشد.</p>}
                            <Button type={"submit"} className={'btn btn-primary'}>ورود</Button>
                        </form>
                        <div>
                            <br/>
                            <p> تا کنون در سایت ثبت نام نکرده اید؟</p>
                            <Link to={"/register"} className='btn btn-default'>ثبت نام</Link>
                        </div>
                    </Col>
                </Row>
            </Grid>
        )
    }
}

const mapStateToProps = (state) => {
    return {
        authentication: state.authentication
    };
};

export default connect(mapStateToProps)(withRouter(LoginComponent));