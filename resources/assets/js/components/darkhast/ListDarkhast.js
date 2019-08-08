import React, {Component} from 'react';
import {Link} from 'react-router-dom';

var moment = require('moment-jalaali')
import axios from 'axios';
import _ from 'lodash';
import {ACCESS_TOKEN} from "../../api/strings";
import GridEstate from "../GridEstate";

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

class ListDarkhast extends Component {
    constructor() {
        super();
        this.state = {
            user: [],
            darkhasts: [],
            similarEstates: [],
            isAuthenticated: false,
            regions: [],
            types: {
                1: {id: 1, name: "آپارتمان"},
                2: {id: 2, name: "مغازه/تجاری"},
                3: {id: 3, name: "اداری"},
                4: {id: 4, name: "زمین/کلنگی"},
                5: {id: 5, name: "خانه/ویلا"},
                6: {id: 6, name: "باغ و کشاورزی"},
                7: {id: 7, name: "کارخانه/کارگاه"},
            },
            months: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'],
            page: 1,
            showDeleteModal: false,
            showDeleteDarkhastModal: false,
            darkhastId: null,

        }
    }

    currency(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
getUserDarkhasts = () =>{
    const user_code = this.props.match.params.ui;
    this.setState({
        userCode: user_code
    });
    axios.get(`/api/getUserDarkhasts`, {params: {user_code}})

        .then(response => {
            const {user, darkhasts, similarEstates} = response.data;
            console.log('similarEstates:', typeof similarEstates, _.isEmpty({similarEstates}), similarEstates.length);
            this.setState({
                user,
                darkhasts,
                similarEstates,
                isAuthenticated: true,
            });
        });
}
    componentDidMount() {

        axios.get('/api/getAllRegions').then(response => {
            this.setState({
                regions: _.mapKeys(response.data, 'id'),
            },()=>this.getUserDarkhasts());
        });

    }

    showDeleteModale = (e) => {
        e.preventDefault();
        this.setState({showDeleteModal: true});
    };

    showDeleteDarkhastModal = (darkhastId) => {
        this.setState({
            showDeleteDarkhastModal: true,
            darkhastId
        })
    }
    removeSimilarEstate = (estateId) => {
        console.log('removeSimilarEstate', estateId, 'user_code =', this.state.userCode)
        // const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        // let headers;
        // if (access_token !== null) {
        //     headers = {
        //         Accept: "application/json",
        //         Authorization: `Bearer ${access_token}`
        //     };
        // }
        const user_code = this.state.userCode;
        axios.post('/api/remove-darkhast-estate', {estate_id: estateId, user_code})
            .then(response => {
                const {similarEstates} = response.data;
                this.setState({
                    similarEstates,
                })
            })
        ;
    };

    removeDarkhast = () => {
        // const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        // if (access_token !== null) {
        //     const headers = {
        //         Accept: "application/json",
        //         Authorization: `Bearer ${access_token}`
        //     };
        const params = {
            id: this.state.darkhastId,
            user_code: this.state.userCode
        };
        // axios.post('/api/remove-darkhast-by-id', params, {headers: {...headers}})
        axios.post('/api/remove-darkhast-by-id', params)
            .then(response => {
                const {darkhasts, similarEstates} = response.data;

                this.setState({
                    darkhasts,
                    similarEstates,
                    showDeleteDarkhastModal: false
                });
            }).catch(error => {
            console.log(error);
        })
        // }
    };
    removeAllDarkhsts = () => {
        // const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        // if (access_token !== null) {
        //     const headers = {
        //         Accept: "application/json",
        //         Authorization: `Bearer ${access_token}`
        //     };
        //     axios.get('/api/remove-all-darkhasts', {headers: {...headers}}).then(response => {
        const user_code = this.state.userCode;
        axios.get(`/api/remove-all-darkhasts`,{params:{user_code}})
            .then(response => {
                this.setState({
                    darkhasts: [],
                    similarEstates: [],
                    showDeleteModal: false
                });
            }).catch(error => {
            console.log(error);
        })
}

hideDeleteModal = (e) => {
    e.preventDefault();
    this.setState({
        showDeleteModal: false,
    })
};
hideDeleteDarkhastModal = (e) => {
    e.preventDefault();
    this.setState({
        showDeleteDarkhastModal: false,
    })
};

render()
{
    const {user, darkhasts, regions, types, similarEstates, months} = this.state;
    return (
        <div>
            <Modal show={this.state.showDeleteModal} handleClose={this.hideDeleteModal}>
                <div className="modal-dialog modal-sm">
                    <div className="modal-content">
                        <div className="modal-body">
                            <h5>آیا برای حذف کلیه درخواست ها مطمئن هستید؟</h5>
                            <button onClick={this.removeAllDarkhsts} className="btn btn-success removes"
                                    role="button"> پاک شود
                            </button>
                            <button onClick={this.hideDeleteModal} type="button"
                                    className="btn btn-danger pull-left" data-dismiss="modal">انصراف
                            </button>

                        </div>
                    </div>
                </div>
            </Modal>
            <Modal show={this.state.showDeleteDarkhastModal} handleClose={this.hideDeleteDarkhastModal}>
                <div className="modal-dialog modal-sm">
                    <div className="modal-content">

                        <div className="modal-body">
                            <h5>آیا از حذف درخواست مطمئن هستید؟</h5>
                            <button onClick={this.removeDarkhast.bind(this)}
                                    className="btn btn-success removes"
                                    role="button"> پاک شود
                            </button>
                            <button onClick={this.hideDeleteDarkhastModal} type="button"
                                    className="btn btn-danger pull-left" data-dismiss="modal">انصراف
                            </button>

                        </div>
                    </div>
                </div>
            </Modal>

            <section id="content">
                <div className="list-registers">
                    <div className=" post-page">
                        <div className="ui fluid card breadcrumb-container">
                            <div className="content">
                                <button className="ui button back-button" role="button"><Link to="/">صفحه
                                    اصلی</Link></button>
                                <div className="ui breadcrumb">
                                        <span>
                                            <div className="section">
                                                <Link title="درخواست جدید خرید ملک" to="/sabt/sell"> درخواست خرید <span
                                                    className="col-xs-hidden">ملک</span></Link>
                                            </div>
                                        </span>
                                    <span>/<div className="section">
                                           <Link title="درخواست جدید اجاره ملک"
                                                 to="/sabt/rent"> درخواست اجاره <span
                                               className="col-xs-hidden">ملک</span></Link>
                                        </div>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div className="content-list">
                            <p className="pull-right info-person">
                                نام : {user.name} <br/>
                                شماره موبایل : {user.phone}<br/>
                                ایمیل :
                                <ul className="comma-list">
                                    {user.emails && (user.emails.map(email => (
                                        <li key={email.id}>{email.email} </li>)))}
                                </ul>
                            </p>
                            <div className="clearfix"></div>
                            <div id="exTab">
                                <ul className="nav nav-pills col-xs-no-padding">
                                    <li onClick={() => this.setState({page: 1})}
                                        className={this.state.page === 1 ? 'active' : ''}>
                                        <a>لیست املاک</a>
                                    </li>
                                    <li onClick={() => this.setState({page: 2})}
                                        className={this.state.page === 2 ? 'active' : ''}>
                                        <a>تاریخچه</a>
                                    </li>
                                </ul>
                                <div className="tab-content clearfix">
                                    {this.state.page === 1 &&
                                    <div className="tab-pane active">
                                        <div className="list-group list">
                                            {similarEstates.length === 0 ?
                                                <div className="col-xs-12 col-xs-no-padding"
                                                     style={{paddingRight: '15px'}}>
                                                    <div className="my-message ">
                                                        <p className="ui" style={{float: 'right', width: '100%'}}>
                                                            هم اکنون ملکی مشابه درخواست شما ثبت نشده است. در صورت
                                                            ثبت
                                                            ملک مشابه درخواست شما به وسیله پیامک در جریان گذاشته
                                                            خواهید
                                                            شد.
                                                        </p>
                                                    </div>
                                                </div>
                                                :
                                                _.map(similarEstates, (items, index) => (

                                                    <div key={index}>
                                                        <div
                                                            className="date-list col-md-12 col-xs-12 col-xs-no-padding">
                                                            <p className="date-day"><i
                                                                className="fa fa-caret-down"> </i> &nbsp;
                                                                {moment(index, 'YYYY-M-D').local('fa').format('jD')} {months[moment(index, 'YYYY-M-D').jMonth()]} {moment(index, 'YYYY-M-D').local('fa').format('jYYYY')}
                                                            </p>
                                                        </div>
                                                        {items.map(estate => (
                                                            <div key={estate.id}
                                                                 className=" col-lg-4 col-sm-6 col-xs-12 content-box col-xs-no-padding">
                                                                <GridEstate estate={estate}
                                                                            remove={this.removeSimilarEstate.bind(this)}>
                                                                    <button type="button"
                                                                            className="delete-btn">
                                                                        <i aria-hidden="true"
                                                                           className="fa fa-trash-o"> </i>
                                                                    </button>
                                                                </GridEstate>
                                                            </div>
                                                        ))}
                                                    </div>
                                                ))
                                            }
                                        </div>
                                    </div>
                                    }
                                    {this.state.page === 2 &&
                                    <div className="tab-pane active" id="2b">
                                        {darkhasts.map(darkhast => (
                                            <div key={darkhast.id} className="col-md-12 history-item content-box">
                                                <div className="col-md-1">
                                                    <a href="#"
                                                       onClick={this.showDeleteDarkhastModal.bind(this, darkhast.id)}
                                                       className="fa fa-times remove"></a>
                                                </div>
                                                <div className="col-md-11">درخواست یک
                                                    {types[darkhast.type_id].name} در
                                                    محدوده {darkhast.region_ids && darkhast.region_ids.map(regionId => (
                                                        <span key={regionId}>{regions[regionId].name} ,</span>))} به
                                                    متراژ {this.currency(darkhast.min_area)} متر
                                                    تا {darkhast.max_area ? darkhast.max_area + 'متر' : 'بیشترین متراژ'} و
                                                    از
                                                    {darkhast.category === 'sell' ? (<span>{darkhast.min_price ?
                                                        <i> قیمت {this.currency(darkhast.min_price)} تومان </i> : 'کمترین قیمت'} تا {this.currency(darkhast.max_price)} تومان </span>) : (
                                                        <span>{darkhast.min_mortgage ? this.currency(darkhast.min_mortgage) + 'تومان' : 'کمترین رهن'} تا {this.currency(darkhast.max_mortgage)} تومان رهن و از {darkhast.min_rent ? this.currency(darkhast.min_rent) + 'تومان' : 'کمترین اجاره'} تا {this.currency(darkhast.max_rent)} تومان   اجاره</span>)} {(darkhast.room || darkhast.elevator || darkhast.parking) ? 'دارای ' : ''}
                                                    <ul className={"comma-list"}> {darkhast.room ?
                                                        <li>{darkhast.room} اتاق </li> : ''} {darkhast.elevator ?
                                                        <li> آسانسور</li> : ''} {darkhast.parking ?
                                                        <li>پارکینگ</li> : ''}   </ul>
                                                    جهت {darkhast.category === 'sell' ? 'خرید' : 'اجاره'} توسط
                                                    شما ثبت شده است
                                                </div>
                                            </div>
                                        ))}
                                        <p className="col-md-12 col-xs-12 text-history"> جهت درخواست جدید خرید
                                            <Link to="/darkhast/sell"> اینجا کلیک کنید </Link></p>
                                        <p className="col-md-12 col-xs-12 text-history">جهت درخواست جدید اجاره
                                            <Link to="/darkhast/rent"> اینجا کلیک کنید</Link></p>
                                        {darkhasts.length > 1 && (
                                            <p className="col-md-12 col-xs-12 text-history">جهت حذف کلیه درخواست
                                                ها <a onClick={this.showDeleteModale}
                                                      data-toggle="modal" data-target="#deleteModal">اینجا
                                                    کلیک
                                                    کنید</a></p>
                                        )}
                                    </div>
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}
}

export default ListDarkhast;