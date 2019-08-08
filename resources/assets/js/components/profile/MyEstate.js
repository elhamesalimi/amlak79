import React from 'react';
import axios from 'axios';
import {ACCESS_TOKEN} from "../../api/strings";
import LoadingScreen from "../LoadingScreen";
import GridEstate from "../GridEstate";
import {Link} from "react-router-dom";
import LoginByCode from "../LoginByCode";
import Modal from "../Partials/Modal";
import {connect} from 'react-redux'

class MyEstate extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            noProduct: false,
            isLoading: true,
            isUserLoggedIn: false,
            estates: [],
            showLoginModal: false,
            phone: '',
            activationCode: null,
            invalidCredentials: false,
            activationCodeModal: false
        };
    }

    getUserEstates = () => {
        const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        const headers = {Accept: "application/json", Authorization: `Bearer ${access_token}`};
        console.log('headers', headers);
        this.setState({isLoading: true, isUserLoggedIn: true})
        axios.get('/api/getUserEstates', {headers})
            .then((response) => {
                    console.log('getUserEstates :', response);
                    const estates = response.data;
                    this.setState({
                        estates,
                        isLoading: false
                    });
                }
            ).catch(error => {
            this.setState({
                noProduct: true,
                isLoading: false
            })
        })
    };
    handleCloseLoginModal = () => {
        this.setState({
            activationCodeModal: false
        })
    };

    componentWillReceiveProps(newProps) {
        if (newProps.authentication.isAuthenticated) {
            this.setState({
                isUserLoggedIn: true,
                isLoading: true
            })
            this.getUserEstates();

        } else {
            this.setState({
                isUserLoggedIn: false,
                isLoading: false
            })
        }
        console.log('Component WILL RECIEVE PROPS!')
    }

    componentDidMount() {
        console.log('Component Did Mount!')

        if (this.props.authentication.isAuthenticated) {
            const isUserLoggedIn = true;
            this.setState({
                isUserLoggedIn
            })
            this.getUserEstates();
        } else {
            this.setState({
                isLoading: false,
                isUserLoggedIn: false,
            })
        }
        // this.getUserEstates();
        // .catch((error) => {
        //     console.log(error.response);

        // });
        // const page = this.props.match.params.type;
        // if (page === 'my-estate' || page === 'bookmarks') {
        //     this.setState({
        //         page
        //     })
        // }

    }

    switchStatus = (status) => {
        switch (status) {
            case 'PUBLISHED':
                return 'منتشر شده';
                break;
            case 'PENDING':
                return 'منتظر تایید';
                break;
            case 'REMOVED':
                return 'منتظر حذف';
                break;
            case 'WAITING':
                return 'در حال بررسی';
                break;
            case 'FAILED':
                return 'منتظر کد تایید';
                break;
            case 'DRAFT':
                return 'حذف شده';
                break;
        }
    };
    handleLogin = () => {
        this.setState({
            showLoginModal: true,
        })
    };
    hideLoginModal = () => {
        this.setState({
            showLoginModal: false,
        })
    };
    handlePhoneChange = (e) => {
        this.setState({
            phone: e.target.value,
        })
    };
    sendActivationCode = (e) => {
        e.preventDefault();
        const {phone} = this.state;
        const code = Math.floor(1000 + Math.random() * 9000);
        this.setState({activation_code: code});
        axios.get(`/api/set-password-send-sms/${phone}/${code}`)
            .then(response => {
                    this.setState({
                        counter: 60,
                        activationCodeModal: true
                    });
                }
            ).catch(error => {
            console.log(error);
        })
    };
    editPhone = (e)=>{
        e.preventDefault();
        this.setState({
            showLoginModal:true,
            activationCodeModal: false,
        })
    }

    render() {
        const {isUserLoggedIn, isLoading, estates, noProduct} = this.state;

        return (
            <div style={{minHeight: '150px'}}>
                {isLoading ?
                    <LoadingScreen/>
                    : isUserLoggedIn ?
                        estates.length > 0 ?
                            estates.map(estate => (
                                <div className="my-estate box">
                                    <div className="col-sm-9">
                                        <GridEstate estate={estate}/>
                                    </div>
                                    <div className="col-sm-3 col-xs-12 text-center">
                                        <p className="col-sm-12 col-xs-4 col-xs-no-padding"><strong>وضعیت ملک:</strong>
                                        </p>
                                        <p className="col-sm-12 col-xs-4 col-xs-no-padding">{this.switchStatus(estate.status)}</p>
                                        <p className={" col-sm-12 col-xs-4 pull-left col-xs-no-padding"}>
                                            <button className={'manage-btn'}>
                                                <Link to={`/manage/${estate.code}`}> مدیریت ملک
                                                </Link>
                                            </button>
                                        </p>
                                    </div>
                                    <div className={"clearfix"}></div>
                                </div>
                            ))
                            :
                            <div className="login-message">
                                شما ملکی ثبت نکرده اید برای ثبت ملک به منوی ثبت ملک مراجعه نمایید.

                            </div>
                        :<div className="login-message">
                            برای مشاهده و مدیریت آگهی‌ها وارد حساب کاربری خود
                            شوید

                            <button className="ui button login-btn" role="button" onClick={this.handleLogin}>ورود و ثبت
                                نام</button>
                        </div>
                }

                <Modal show={this.state.showLoginModal} handleClose={this.hideLoginModal}>
                    <div className="panel panel-default ">
                        <div className="panel-heading">
                            {this.state.activationCodeModal ? 'تایید شماره تماس' : " ورود و ثبت نام"}
                            <i className="fa fa-times pull-left" onClick={this.hideLoginModal}></i>
                        </div>
                        {/*<form onSubmit={this.handelSubmitLoginForm}>*/}
                        <div className="panel-body">
                            {this.state.activationCodeModal
                                ?
                                <LoginByCode phone={this.state.phone} activationCode={this.state.activationCode}
                                             isAuth={this.hideLoginModal}>
                                    <a  href="#" onClick={this.editPhone}  className="link">تغییر شماره تماس</a>
                                </LoginByCode>
                                :
                                <div>
                                    <div className="form-group ">
                                        <label htmlFor="phone" className={"control-label"}>
                                            شماره تماس:
                                        </label>
                                        <input type="number" name={"phone"} onChange={this.handlePhoneChange}
                                               value={this.state.phone} className={"form-control"}/>

                                        {this.state.invalidCredentials && (
                                            <span
                                                className="bg-danger help-block"> شماره تماس وارد شده صحیح نمی باشد.</span>
                                        )}
                                    </div>
                                    <a className={`btn btn-danger pull-left `}
                                       onClick={this.sendActivationCode}> دریافت کد تایید
                                    </a>
                                </div>
                            }
                        </div>
                    </div>
                </Modal>

            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        authentication: state.authentication
    };
};
export default connect(mapStateToProps)(MyEstate);