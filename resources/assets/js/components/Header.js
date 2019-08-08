import React from 'react';
import {Navbar, NavItem, Header, Toggle, Brand, Collapse, Nav, NavDropdown, MenuItem} from 'react-bootstrap';
import {Link, withRouter} from 'react-router-dom';
import {connect} from 'react-redux';
import Divider from 'material-ui/Divider';
import {filterEstates} from '../actions/estates';
import {changeSelectedCity, changeDisabledCity} from '../actions/city';
import {ACCESS_TOKEN} from "../api/strings";

class HeaderTest extends React.Component {

    state = {
        isOpenElectronics: false,
        isOpenBook: false,
        isOpenHome: false,
        placeholder: "....",
        cities: [
            {id: 1, province_id: 18, name: "قزوین", slug: "qazvin"},
            {id: 2, province_id: 18, name: "آبیک", slug: "abyek"},
            {id: 3, province_id: 18, name: "تاکستان", slug: "takestan"},
            {id: 4, province_id: 18, name: "الوند", slug: "alvand"},
            {id: 5, province_id: 18, name: "محمدیه", slug: "mohammadieh"},
            {id: 6, province_id: 18, name: "مهرگان", slug: "mehregan"},
            {id: 7, province_id: 18, name: "الموت", slug: "alamut"},
            {id: 8, province_id: 18, name: "شریفیه", slug: "sharifiye"},
            {id: 9, province_id: 18, name: "کورانه", slug: "kouraneh"},
            {id: 10, province_id: 18, name: "آبگرم", slug: "abgarm"},
            {id: 11, province_id: 18, name: "رشتقون", slug: "Rashteghoun"},
            {id: 12, province_id: 18, name: "بیدستان", slug: "bidestan"}
        ],
        selectedCity: "",
        searchBoxText: "",
        shoppingCartOpen: false,
        menuItemMUI: ["Log In", "Register"],
        open: false
    };

    cityStateChangeHelper = (city) => {
        this.setState((prevState) => {
            return {
                cities:
                    prevState.cities.concat(prevState.citySelected).filter((menuItem) => (
                        menuItem !== city
                    )),
                citySelected: city
            }
        });

        this.categoryClickHandler(city);
        // this.props.filterEstates(city);
    };

    changeMenuMUIOptionsAuthenticated = () => {
        this.setState(() => ({menuItemMUI: ["My account", "My Orders", "Divider", "Log out"]}));
    };

    changeMenuMUIOptionsUnauthenticated = () => {
        this.setState(() => ({menuItemMUI: ["Log In", "Register"]}));
    };

    componentWillReceiveProps(nextProps) {
        const {selectedCity} = nextProps;
        this.setState({selectedCity: selectedCity});

        //     let currentPath = this.props.location.pathname.toString();
        //     let nextPath = nextProps.location.pathname.toString();
        //     console.log('currentPath :',currentPath);
        //     console.log('nextPath :',currentPath);
        //
        //     if(currentPath !== nextPath || this.props.authentication.isAuthenticated !== nextProps.authentication.isAuthenticated){
        //         // path is been changed
        //         ;
        //         this.cityStateChangeHelper(nextPath);
        //         if(nextProps.authentication.isAuthenticated){
        //             this.changeMenuMUIOptionsAuthenticated();
        //         }
        //         else{
        //             this.changeMenuMUIOptionsUnauthenticated();
        //         }
        //     }
    }

    componentDidMount() {
        this.props.changeDisabledCity(false);
        // axios.get('/api/cities').then((response) => {
        //     this.setState({
        //         cities : response.data,
        //     })
        // }).catch(error=>{
        //     console.log(error)
        // });
        if (this.props.authentication.isAuthenticated) {
            this.changeMenuMUIOptionsAuthenticated();
        }
        else {
            this.changeMenuMUIOptionsUnauthenticated();
        }
    }

    categoryOnHoverIn = (e) => {
        switch (e.target.id) {
            case "electronics-nav-dropdown": {
                this.setState({isOpenElectronics: true});
                break;
            }
            case "books-nav-dropdown": {
                this.setState({isOpenBook: true});
                break;
            }
            case "home-requirements-nav-dropdown": {
                this.setState({isOpenHome: true});
                break;
            }
        }

    };

    categoryOnHoverOut = () => {
        this.setState(() => {
            return {
                isOpenElectronics: false,
                isOpenBook: false,
                isOpenHome: false
            }
        });

    };

    categoryClickHandler = (routeName) => {
        this.props.history.push(routeName);

    };

    onSearchFormSubmit = (e) => {
        e.preventDefault();
        let searchCategorySelected = this.state.citySelected;
        let searchQuery = this.state.searchBoxText;
        if (searchQuery.length > 1) {
            this.props.history.push("/search/" + searchCategorySelected.toLowerCase() + "/" + searchQuery);
        }
        else {
            this.input.focus();
        }

    };

    menuOptionsClick = (menuItemName) => {
        this.setState(() => ({open: false}));
        const url = "/".concat(menuItemName.split(" ").join("").toLowerCase());
        this.props.history.push(url);
    };

    searchBoxChange = (e) => {
        let searchBoxText = e.target.value;
        if (searchBoxText.length < 25) {
            this.setState(() => ({searchBoxText}));
        }
    };

    handleCityChangee = (event) => {
        const city = event.target.value;
        console.log('city: ', city);
        this.setState({selectedCity: city});
        this.props.changeSelectedCity(city);
        this.props.history.push(city);

        // this.cityStateChangeHelper(city);
    };

    handleCityChange = (event) => {
        const city = event.target.value;
        this.cityStateChangeHelper(city);
    };

    shoppingCartModalShow = () => {
        this.setState(() => ({shoppingCartOpen: true}));
    };

    shoppingCartModalHide = () => {
        this.setState(() => ({shoppingCartOpen: false}));
    };

    handleUserAccountClick = (event) => {
        // This prevents ghost click.
        event.preventDefault();

        this.setState({
            open: true,
            anchorEl: event.currentTarget,
        });
    };

    handleUserAccountClose = () => {
        this.setState({
            open: false,
        });
    };

    render() {
        let shoppingCartTotal = this.props.shoppingCart ? this.props.shoppingCart.reduce((accumulator, item) => {
            return accumulator + item.quantity;
        }, 0) : 0;
        return (
            <Navbar inverse collapseOnSelect>

                <Navbar.Header>

                    <Navbar.Brand>
                        <Link to="/"><img src="/asset/images/logo.png" alt="" style={{maxWidth: '145px'}}
                                          className="image-responsive"/></Link>
                    </Navbar.Brand>
                    <Navbar.Toggle/>
                    <div className="city select  col-md-0">
                        <select value={this.state.selectedCity} disabled={this.props.disabledSelectCity || false}
                                onChange={this.handleCityChangee} name="city" id="city">
                            {this.state.cities.map((city, i) => (
                                    <option value={city.slug} key={i}>{city.name}</option>
                                )
                            )}
                        </select>
                    </div>
                </Navbar.Header>
                <Navbar.Collapse>

                    <Nav>
                        <NavItem eventKey={1}>
                            <Link to="/">صفحه اصلی</Link>
                        </NavItem>
                        <NavDropdown eventKey={2} title="ثبت ملک" id="basic-nav-dropdown">
                            <MenuItem eventKey={2.1}>
                                <Link to='/sabt/sell'> برای فروش ملک</Link></MenuItem>
                            <MenuItem eventKey={2.2}>
                                <Link to='/sabt/rent'> برای رهن و اجاره ملک</Link>
                            </MenuItem>
                        </NavDropdown>
                        <NavDropdown eventKey={3} title="درخواست ملک" id="basic-nav-dropdown">
                            <MenuItem eventKey={3.1}>
                                <Link to="/darkhast/sell"> درخواست خرید ملک</Link>
                            </MenuItem>
                            <MenuItem eventKey={3.2}>
                                <Link to="/darkhast/rent"> درخواست اجاره ملک</Link>
                            </MenuItem>
                        </NavDropdown>
                        <NavItem eventKey={4}>
                            <Link to="/my-profile"> ملک های من</Link>
                        </NavItem>
                        {/*<NavItem eventKey={5}>*/}
                            {/*<Link to="/experts"> ملک های کارشناس</Link>*/}
                        {/*</NavItem>*/}
                        <NavItem eventKey={6} href="#">
                            تماس با ما
                        </NavItem>
                        {(this.props.authentication.isAuthenticated && window.localStorage.getItem(ACCESS_TOKEN) !==
                        null)&&
                        <NavItem eventKey={7}>
                            <Link to="/logout">خروج</Link>
                        </NavItem>
                    }

                    </Nav>
                </Navbar.Collapse>
                <div className="city select col-sm-0 col-xs-0">
                    <select value={this.state.selectedCity} disabled={this.props.disabledSelectCity || false}
                            onChange={this.handleCityChangee} name="city" id="city">
                        {this.state.cities.map((city, i) => (
                                <option value={city.slug} key={i}>{city.name}</option>
                            )
                        )}
                    </select>
                </div>
            </Navbar>


        )
    }
}

const mapStateToProps = (state) => {
    return {
        disabledSelectCity: state.disabledSelectCity || false,
        selectedCity: state.selectedCity,
        estates: state.estates,
        shoppingCart: state.shoppingCart,
        authentication: state.authentication
    };
};


export default withRouter(connect(mapStateToProps, {
    filterEstates,
    changeSelectedCity,
    changeDisabledCity
})(HeaderTest));