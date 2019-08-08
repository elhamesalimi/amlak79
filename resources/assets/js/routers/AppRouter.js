import React from 'react';
import Header from '../components/Header';
import HomePage from '../components/HomePage';
import Sabt from '../components/Sabt';
import DarkhastRent from '../components/darkhast/DarkhastRent';
import DarkhastSell from '../components/darkhast/DarkhastSell';
import ListDarkhast from "../components/darkhast/ListDarkhast";
import Estate from '../components/Estate';
import { BrowserRouter, Switch, Route, Link } from 'react-router-dom';
import LoginComponent from "../components/LoginComponent";
import LogoutComponent from "../components/LogoutComponent";
import NotFoundPage from "../components/NotFoundPage";
import Manage from "../components/Manage";
import Edit from "../components/Edit";
import Divar from "../components/Divar";
import Slick from "../components/Slick";

const appRouter = () => (
    <BrowserRouter>
        <div>
            <Header disabledSelectCity={false}/>
            <Switch>
                <Route path="/carousel" component={Slick} />
                <Route path="/login" exact={true} component={LoginComponent}/>
                <Route path="/logout" exact={true} component={LogoutComponent}/>
                <Route path="/manage/:estateCode/:email_from?" exact={true} component={Manage}/>
                <Route path="/my-profile" exact={true} component={Divar}/>
                {/*<Route path="/my-profile/:type" exact={true} component={Divar}/>*/}
                {/*<Route path="/estate/:id" key=":id" exact={true} component={Estate}/>*/}
                <Route
                    exact={true}
                    path="/estate/:id"
                    render={props => <Estate key={props.match.params.id || 'empty'} /> }
                />
                <Route path="/estate/:id/edit" exact={true} component={Edit}/>
                <Route path="/sabt/:category" exact={true} component={Sabt}/>
                <Route path="/darkhast/sell" exact={true} component={DarkhastSell}/>
                <Route path="/darkhast/rent" exact={true} component={DarkhastRent}/>
                <Route path="/darkhast/list/:ui?" exact={true} component={ListDarkhast}/>

                <Route path="/:city?" exact={true}
                       render={(props) => (<HomePage {...props}
                                                          />)}/>
                <Route exact={true} component={NotFoundPage} />
            </Switch>
        </div>
    </BrowserRouter>
);

export default appRouter;