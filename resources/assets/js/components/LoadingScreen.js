import React from 'react';

const LoadingScreen = ({children}) => (
    <div className={"loading"}>
    <i className={"fa fa-refresh fa-spin fa-3x text-success"}></i>
        <br/>
        {children}
    </div>
);

export default LoadingScreen;