import React, {Component} from 'react'
import axios from 'axios';
import LoadingScreen from "../LoadingScreen";
import GridEstate from "../GridEstate";

class Bookmark extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoading: true,
            estates: [],
            noEstates: false,
        }
    }

    componentDidMount() {
        this.setState({isLoading: true});
        let myBookmarkedEstate = window.localStorage.getItem('myBookmarkedEstate');
        myBookmarkedEstate = (myBookmarkedEstate) ? JSON.parse(myBookmarkedEstate) : [];
        if (myBookmarkedEstate.length > 0) {
            const estateIds = JSON.stringify(myBookmarkedEstate);
            axios.get(`/api/bookmark-estates/${estateIds}`)
                .then(response => {
                    const estates = response.data;
                    if (estates.length) {

                        this.setState({estates}, () => this.setState({isLoading: false, noEstates: false}));
                    } else {
                        this.setState({noEstates: true, isLoading: false})

                    }
                }).catch(error => {
                alert(error);
                console.log(error);
                this.setState({noEstates: true, isLoading: false})
            });
        } else {
            this.setState({noEstates: true, isLoading: false})
        }


    }

    removeBookmark = (id) => {
        console.log('remove Bookmark Click;')
        let bookmarked = window.localStorage.getItem('myBookmarkedEstate');
        let myBookmarkedEstate = (bookmarked) ? JSON.parse(bookmarked) : [];
        myBookmarkedEstate.splice(myBookmarkedEstate.indexOf(parseInt(id)), 1);
        window.localStorage.setItem('myBookmarkedEstate', JSON.stringify(myBookmarkedEstate));
        let estates = this.state.estates;
        let filterEstates = estates.filter(function (item) {
            return item.id !== id;
        });
        this.setState({
            estates: filterEstates
        });
    };

    render() {
        const {isLoading, estates, noEstates} = this.state;

        return (
            <div style={{minHeight: '150px'}}>
                {isLoading ?
                    <LoadingScreen/>
                    :
                    estates.length > 0 ?
                        estates.map(estate => (
                            <GridEstate estate={estate} remove={this.removeBookmark.bind(this)}>
                                <button type="button"
                                        className="delete-btn">
                                    <i aria-hidden="true" className="fa fa-trash-o"> </i>
                                </button>
                            </GridEstate>
                        ))
                        :
                        <div className="login-message">شما آگهی نشان شده‌ای ندارید.
                            <div className="login-message__extra">با زدن روی دکمه “نشان کردن” در صفحه آگهی، می‌توانید
                                آن‌ها را
                                در
                                این لیست ذخیره کنید
                            </div>
                            <img
                                src="data:image/png;base64,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"
                                alt="با زدن روی دکمه “نشان کردن” در صفحه آگهی، می‌توانید آن‌ها را در این لیست ذخیره کنید"/>
                        </div>
                }
            </div>
        )
    }
}

export default Bookmark