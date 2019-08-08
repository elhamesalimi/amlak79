import React, {Component} from 'react'
import axios from 'axios'
import {loginAPI} from "../api/apiURLs";
import {Link} from 'react-router-dom';
import LoadingScreen from "./LoadingScreen";
import {ACCESS_TOKEN, REFRESH_TOKEN} from "../api/strings";
import {loginUser,logoutUser} from "../actions/authentication";
import Gallery from "./Partials/Gallery";

class Manage extends Component {

    constructor(props) {
        super(props);
        const myManagePost = [];
        // myManagePost.push(window.localStorage.getItem('myManagePosts'));
        const estateCode = this.props.match.params.estateCode;

        // window.localStorage.setItem('myManagePosts', myManagePost);
        this.state = {
            activation_code: '',
            preview: true,
            showDeleteModal: false,
            noProduct: false,
            isLoading: true,
            counter: 60,
            sendSms: false
        }
        if (document.getElementById("activation_code")) {
            document.getElementById("activation_code").focus();
        }
    }

    tick = () => {
        if (this.state.counter > 0) {
            this.setState({counter: this.state.counter - 1});
        }

    };

    componentDidMount() {

        this.interval = setInterval(this.tick, 1000);
        const estateCode = this.props.match.params.estateCode;
        const email_from = this.props.match.params.email_from;

        const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        let headers;
        // if (this.props.authentication.isAuthenticated && access_token !== null) {
        if (access_token !== null) {
            headers = {
                Accept: "application/json",
                Authorization: `Bearer ${access_token}`
            };
        }
        axios.get(`/api/manage/${estateCode}/${email_from}`, {headers: {...headers}})
            .then(response => {
                const {estate, fields, facilities} = response.data;
                this.setState({
                    estate,
                    fields,
                    facilities,
                });
                if(estate.status==='FAILED'){
                    console.log('status is FAILD');
                    this.sendActivationCode();
                    this.openAccept();
                }
            }).catch(error => {
            this.setState({
                noProduct: true,
                isLoading: false,
            })
        });
    }

    openAccept = () => {
            this.setState({
                sendSms: true
            })
    };

    sendActivationCode = () => {
        const {phone} = this.state.estate.owner;
        const code = Math.floor(1000 + Math.random() * 9000);
        // this.setState({activation_code:code});
        const params = {phone, code};
        axios.get(`/api/set-password-send-sms/${phone}/${code}`)
            .then(response => {
                    this.setState({counter: 60})
                }
            ).catch(error => {
            console.log(error);
        })
    };
    hideActiveModal = () => {
        this.setState({sendSms: false});
    };
    hideDeleteModal = () => {
        this.setState({
            showDeleteModal: false,
        })
    };
    handleInput = (e) => {
        const target = e.target;
        this.setState({
            activation_code: target.value,
        })
    };

    handleShowDeleteModal = () => {
        this.setState({
            showDeleteModal: true,
        })
    };
    handelSubmitFormActive = (e) => {
        e.preventDefault();
        const {phone} = this.state.estate.owner;
        const {activation_code} = this.state;

        if (activation_code.length > 0) {
            this.setState(() => ({isLoading: true}));
            const data = {
                grant_type: "password",
                client_id: "2",
                client_secret: window.Laravel.client_secret,
                username: phone,
                password: activation_code,
                scope: "*",
                guard: 'api'
            };

            axios.post(loginAPI, data)
                .then((response) => {
                    window.localStorage.setItem(ACCESS_TOKEN, response.data.access_token);
                    window.localStorage.setItem(REFRESH_TOKEN, response.data.refresh_token);
                    this.setState({
                        sendSms: false,
                    });
                    // this.props.dispatch(loginUser());

                    const estateId = this.state.estate.id;
                    axios.get(`/api/change-Fail-status/${estateId}`).then(
                        this.setState(prevState => ({
                                estate: {
                                    ...prevState.estate,
                                    status: 'PENDING'
                                }
                            })
                        )
                    ).catch(error => {
                        this.props.dispatch(logoutUser());
                        console.log(error)
                    });
                    // axios.get(`/api/set-active-phone/${phone}`);

                    this.setState({
                        invalidCredentials: false,
                    })
                })
                .catch((error) => {
                    this.setState({
                        invalidCredentials: true,
                        isLoading: false
                    });
                });
        }
    };
    deleteEstate = () => {
        const estateId = this.state.estate.id;
        axios.get(`/api/remove-estate/${estateId}`)
            .then(response => {
                this.setState(prevState => ({
                    showDeleteModal: false,
                    showDeleteAlert: true,
                    estate: {
                        ...prevState.estate,
                        status: 'REMOVED'
                    }
                }))
            })
    };
    switchStatus = (status) => {
        switch (status) {
            case 'PUBLISHED':
                break;
                return <div className="alert alert-success">ملک شما منتشر شده است.</div>;
            case 'PENDING':
                return <div className="alert alert-info">ملک شما ثبت شده است و منتظر تایید کارشناسان می باشد.</div>;
                break;
            case 'REMOVED':
                return <div className="alert alert-warning">ملک توسط شما حذف شده است و منتظر حذف نهایی توسط کارشناسان می
                    باشد.</div>;
                break;
            case 'WAITING':
                return <div className="alert alert-info">ملک شما ثبت شده است و در حال بررسی توسط کارشناسان می
                    باشد.</div>;
                break;
            case 'FAILED':
                return <div className="alert alert-warning">جهت ثبت نهایی ملک <a onClick={this.openAccept}
                                                                                 className="link">کد تایید </a> را وارد
                    نمایید. </div>;
                break;
            case 'DRAFT':
                return <div className="alert alert-info">ملک شما توسط کارشناسان حذف شده است.</div>;
                break;

        }
    };

    render() {
        const {estate, fields, facilities} = this.state;

        if (!estate) {
            if (this.state.noProduct) {
                return (
                    <div id="content" className="alert alert-warning">
                        ملکی برای مدیریت وجود ندارد.
                    </div>
                )
            }
            return (
                <LoadingScreen>
                    <span>در حال دریافت اطلاعات ملک...</span>
                </LoadingScreen>)
        } else {
            return (
                <div className="container">

                    <Modal show={this.state.showDeleteModal} handleClose={this.hideDeleteModal}>
                        <div className="panel panel-danger ">
                            <div className="panel-heading"> هشدار حذف!</div>
                            <div className="panel-body">
                                <div className="alert alert-danger text-center">
                                    از حذف آگهی مطمئن هستید؟

                                </div>
                                <button className="btn btn-danger pull-left col-xs-offset-4"
                                        onClick={this.deleteEstate}>بله
                                </button>
                                <button className="btn btn-warning pull-right col-xs-offset-1"
                                        onClick={this.hideDeleteModal}>انصراف
                                </button>
                            </div>
                        </div>
                    </Modal>
                    <Modal show={this.state.sendSms} handleClose={this.hideActiveModal}>
                        <div className="panel panel-default ">
                            <div className="panel-heading">
                                تایید شماره تماس
                                <i className="fa fa-times pull-left" onClick={this.hideActiveModal}></i>
                            </div>
                            <form onSubmit={this.handelSubmitFormActive}>
                                <input type="hidden" name={"phone"} value={estate.owner.phone}/>
                                <div className="panel-body">
                                    <div className="alert alert-success text-center">کد تایید به
                                        شماره {estate.owner.phone} پیامک شد.
                                    </div>
                                    <div className="form-group ">
                                        <p className="text-center">{this.state.counter}</p>
                                        <input type="number" onChange={this.handleInput}
                                               id="activation_code"
                                               required={true} name="activation_code" className="form-control"/>
                                        {this.state.invalidCredentials && (
                                            <span
                                                className="bg-danger help-block">کد تایید وارد شده صحیح نمی باشد.</span>
                                        )}
                                    </div>
                                    <button type="submit" className="btn btn-default pull-right ">تایید
                                    </button>
                                    <a className={`btn btn-danger pull-left ${!this.state.counter ? '' : 'disabled'}`}
                                       onClick={this.sendActivationCode}>ارسال مجدد کد
                                        تایید</a>
                                </div>
                            </form>
                        </div>
                    </Modal>

                    <div className=" list-registers">
                        <br/>
                        <div className="post-page">
                            <div className="col-xs-12">
                                {this.switchStatus(estate.status)}
                                <div className="content">
                                    <button className="ui button back-button active-btn" role="button"
                                    >پیش نمایش
                                    </button>
                                    <Link
                                        role="button" to={`/estate/${estate.code}/edit`}
                                        className={`${!this.state.sendSms ? 'ui button back-button' : 'ui button back-button disabled'}`}>ویرایش</Link>
                                    <button className="ui button back-button" role="button"
                                            onClick={this.handleShowDeleteModal}>حذف
                                    </button>
                                    {estate.status === 'PUBLISHED' && (
                                        <Link className="ui button back-button" role="button"
                                              to={`/estate/${estate.id}`}>مشاهده آگهی</Link>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="list-registers row">
                        <div className="col-sm-5 col-xs-12">
                            <div className="ui fluid card post-info business-card">
                                <div className="content">
                                    <h3>{estate.title}</h3>
                                    <span className="publishTime">{estate.date} </span>
                                </div>
                            </div>

                            <div className="ui fluid card post-fields">
                                <div className="content">
                                    <div className="info-home">
                                        <table className="table " style={{borderCollapse: 'unset'}}>
                                            <tbody>
                                            <tr>
                                                <td>نام</td>
                                                <td className="tal text-left">{estate.owner.name}</td>
                                            </tr>
                                            <tr>
                                                <td>شماره تماس</td>
                                                <td className="tal">{estate.owner.phone}</td>
                                            </tr>
                                            <tr>اطلاعات ملک :</tr>
                                            <tr>
                                                <td>کد ملک</td>
                                                <td className={"tal"}> {estate.id} </td>
                                            </tr>
                                            <tr>
                                                <td>{estate.category === 'rent' ? 'رهن' : 'قیمت کل'}</td>
                                                <td className={"tal"}> {estate.total_price.toLocaleString("en", {minimumFractionDigits: 0})} تومان</td>
                                            </tr>
                                            <tr>
                                                <td>{estate.category === 'rent' ? 'اجاره' : 'قیمت متر مربع'}</td>
                                                <td className={"tal"}>{estate.price.toLocaleString("en", {minimumFractionDigits: 0})} تومان</td>
                                            </tr>
                                            <tr>
                                                <td>متراژ</td>
                                                <td className={"tal"}>{estate.area.toLocaleString("en", {minimumFractionDigits: 0})}</td>
                                            </tr>
                                            <tr>
                                                <td>موقعیت</td>
                                                <td className={"tal"}>{estate.plan.name}</td>
                                            </tr>

                                            {_.map(estate.fields, (value, i) => (
                                                i === 'description' &&
                                                (
                                                    <tr></tr>
                                                )
                                                ||
                                                i === 'floor' &&
                                                (
                                                    <tr>
                                                        <td>طبقه</td>
                                                        <td className={"tal mines-input"}>
                                                            <ul className={"comma-list"}>{value === '-1' ? 'زیرزمین' : value === '0' ? 'همکف' : estate.fields.floor.map((floor, i) => (
                                                                <li key={i}>{floor}</li>))}</ul>
                                                        </td>
                                                    </tr>
                                                )
                                                ||
                                                i === 'exchange' &&
                                                (
                                                    <tr>
                                                        <td>معاوضه</td>
                                                        <td className={"tal"}>دارد</td>
                                                    </tr>
                                                )
                                                ||
                                                i === 'presell' && (
                                                    <tr>
                                                        <td>پیش فروش</td>
                                                        <td className={"tal"}>دارد</td>
                                                    </tr>)
                                                ||
                                                i === 'delivery' && (
                                                    <tr>
                                                        <td>{fields[i]}</td>
                                                        <td className={"tal"}>{estate.delivery} </td>
                                                    </tr>
                                                )
                                                ||
                                                i === 'has_loan' && (
                                                    <tr></tr>)
                                                ||
                                                i === 'type_cabinet' && (
                                                    <tr></tr>)
                                                ||
                                                i === 'loan_amount' && (
                                                    <tr>
                                                        <td>{fields[i]}</td>
                                                        <td className={"tal"}>{value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")} تومان</td>
                                                    </tr>
                                                )
                                                ||

                                                i === 'address' && (
                                                    <tr></tr>
                                                )
                                                ||
                                                i === 'description' && (
                                                    <tr>
                                                        <td colSpan="2">{value}</td>
                                                    </tr>
                                                )
                                                ||
                                                i === 'more' && (
                                                    <tr>
                                                    </tr>
                                                )
                                                ||
                                                i === 'facilities' && (
                                                    <tr>
                                                    </tr>
                                                )
                                                ||
                                                <tr>
                                                    <td>{fields[i]}</td>
                                                    <td className={"tal"}>{value}</td>
                                                </tr>
                                            ))}

                                            </tbody>
                                            <tbody>
                                            <tr>
                                                <td colSpan="2">آدرس :</td>
                                            </tr>
                                            <tr>
                                                <td colSpan="2" style={{
                                                    borderTop: 'none',
                                                    textAlign: 'justify'
                                                }}>{estate.fields['address']}</td>
                                            </tr>
                                            </tbody>
                                            {estate.fields['description'] && (
                                                <tbody>
                                                <tr>
                                                    <td colSpan="2">توضیحات :</td>
                                                </tr>
                                                <tr>
                                                    <td colSpan="2" style={{
                                                        borderTop: 'none',
                                                        textAlign: 'justify'
                                                    }}>
                                                        <pre>{estate.fields['description']}</pre>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            )}
                                        </table>
                                    </div>
                                    {(estate.fields.facilities && estate.fields.facilities.length > 0) && (
                                        <div className="show-facilities">
                                            <div className="item ">
                                                <label>امکانات</label>
                                                <div className="col-xs-12">
                                                    {estate.fields.facilities.map((facility, i) => (
                                                        facility === 'cabinet' ?
                                                            <span className="p10" key={i}>
                                    کابینت {estate.fields['type_cabinet']}
                                    </span>
                                                            :
                                                            <span className="p10 display-inline-block"
                                                                  key={i}>
                                    {facilities[facility]}
                                    </span>
                                                    ))}
                                                </div>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                        <div className="col-sm-7 col-xs-12 col-xs-nopadding">
                            {!estate.images.length ?
                                <img src="/asset/images/no-image.png" style={{width: '100%'}}
                                     className="img-responsive col-xs-hidden"/>
                                :
                                <Gallery images={estate.images}/>
                            }
                        </div>
                    </div>

                </div>
            )
        }
    }
}

const Modal = ({handleClose, show, children}) => {
    const showHideClassName = show ? 'modal display-block' : 'modal display-none';

    return (
        <div className={showHideClassName}>
            <section className='modal-main col-xs-10 col-sm-6'>
                {children}

            </section>
        </div>
    );
};
export default Manage;