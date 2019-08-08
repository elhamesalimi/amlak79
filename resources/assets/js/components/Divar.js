import React, {Component} from 'react'
import Bookmark from './profile/Bookmark'

import MyEstate from "./profile/MyEstate";

class Divar extends Component {

    constructor(props) {
        super(props);

        this.state = {
            noProduct: false,
            isLoading: false,
            page: 'my-estate',
            noLogin: false,
            estates: []

        }
    }

    componentDidMount() {
        // .catch((error) => {
        //     console.log(error.response);
        //     window.localStorage.removeItem(ACCESS_TOKEN);
        //     this.props.dispatch(logoutUser());
        // });
        // const page = this.props.match.params.type;
        // if (page === 'my-estate' || page === 'bookmarks') {
        //     this.setState({
        //         page
        //     })
        // }

    }

    changePage(page) {
        this.setState({
            page
        })
    }

    render() {
        const {estate} = this.state;
        // if(this.state.noProduct) {
        // }
        // if (!estate) {
        //     if (this.state.noProduct) {
        //         return (
        //             <div className="alert alert-warning">
        //                 ملکی برای مدیریت وجود ندارد.
        //             </div>
        //         )
        //     }
        //     return <LoadingScreen/>
        // // } else {
        const {page} = this.state;
        return (
            <div id="content" className="ui text container">
                <div className="ui padded grid my-divar">
                    <div className="row">
                        <div className="twelve wide column" >
                            <div className="ui pointing secondary top attached menu">
                                <a className={`${page === 'my-estate'?'active' :''} item`} onClick={this.changePage.bind(this, 'my-estate')}
                                > ملک های من </a>
                                <a className={`${page === 'bookmarks'?'active' :''} item`} onClick={this.changePage.bind(this, 'bookmarks')}>آگهی‌های نشان
                                    شده</a>
                            </div>
                            <div className="ui bottom attached segment">
                                {page === 'my-estate' &&
                                <MyEstate/>
                                }
                                {page === 'bookmarks' &&
                                <Bookmark/>
                                }
                                <div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

    // }
}

const
    Modal = ({handleClose, show, children}) => {
        const showHideClassName = show ? 'modal display-block' : 'modal display-none';

        return (
            <div className={showHideClassName}>
                <section className='modal-main col-xs-8 col-sm-6'>
                    {children}

                </section>
            </div>
        );
    };
export default Divar;