import React from 'react';
import {Link} from "react-router-dom";

class GridEstate extends React.Component {

    renderOfferPhotoSwitch(param) {
        switch (param) {
            case 'lux':
                return (<div className="ribbon-box red">
                    <span className="ribbon">لوکس</span>
                </div>);
            case 'underprice':
                return (<div className="ribbon-box blue">
                    <span className="ribbon">زیرقیمت</span>
                </div>);
            case 'special':
                return (<div className="ribbon-box green">
                    <span className="ribbon">ویژه</span>
                </div>);
            default:
                return;
        }
    }
    remove=(id) => {
        console.log('removeBookmark:',id);
        this.props.remove(id);
}

    render() {
        const {estate} = this.props;
        const {fields} = estate;
        return (
            <div id={"gridEstate"} className={"pos-relative"}>
                <Link to={`/estate/${estate.id}`}>
                    {/*<Link to={`/estate/${estate.id}`}>*/}
                    <div className="index-item ">
                        <div className="item-body clearfix">
                            <div className="thumbnail">
                                {this.renderOfferPhotoSwitch(estate.offer)}
                                {(estate.images && estate.images.length > 0) ? (
                                    <img src={`/public_data/images/thumbs160x160/${estate.images.filter(image=>(image.avatar===1)).length > 0? estate.images.filter(image=>(image.avatar===1))[0].uri : estate.images[0].uri  }`}/>
                                ) : (
                                    <img src="/asset/images/no-image.png" alt="" className="img-responsive"
                                         style={{height: '100%', width: '200px'}}/>
                                )}
                                {estate.category === 'rent' && (
                                    <div className="textonimg">
                                        <span> اجاره : {estate.price.toLocaleString("en", {minimumFractionDigits: 0})} تومان </span>
                                    </div>
                                )}

                            </div>
                            <div className="content">
                                <div className="fixed-height">
                                    <p className="title-home"> {estate.title}</p>
                                    {estate.type_id !== 2 &&
                                    <div>
                                        <p className="facilities">
                                            {fields.unit ? fields.unit : ''} {fields.unit ? 'واحده' : ''} {(fields.unit && ((estate.hasOwnProperty('floor') && fields.floor !== null) || 'age' in fields)) ? '|' : ''} {('floor' in fields) ? 'طبقه ' + fields.floor : ''} {(('floor' in fields) && 'age' in fields) ? '|' : ''} {('age' in fields) ? (fields.age === 0 || fields.age === '0') ? 'نوساز' : fields.age + ' ساله' : ''} </p>
                                        {/*{fields.floor > -2 ? fields.floor===-1 ? 'زیرزمین': fields.floor===0 ? 'همکف' :fields.floor[0] : ''} {((fields.floor !== null && fields.floor !== '') && fields.age >-1) ? '|' : ''}*/}
                                        <p className="facilities"> {fields.tarakom ? 'تراکم ' + fields.tarakom : ''} {fields.facilities &&
                                        <span>{fields.facilities.indexOf('elevator') > -1 ? 'آسانسور' : ''} {(fields.facilities.indexOf('elevator') > -1 && fields.facilities.indexOf('parking') > -1) ? '|' : ''} {fields.facilities.indexOf('parking') > -1 ? 'پارکینگ' : ''} {((fields.facilities.indexOf('elevator') > -1) || (fields.facilities.indexOf('parking') > -1 || fields.tarakom) && fields.room) ? '|' : ''} </span>} {fields.room ? fields.room + 'خواب' : ''} </p>
                                    </div>
                                    }
                                </div>
                                <span className="price-home">{estate.category === 'rent' ? 'رهن:' : ''}
                                    <span
                                        className="title price"></span>{estate.total_price.toLocaleString("en", {minimumFractionDigits: 0})} تومان </span>
                                {estate.category !== 'rent' && (
                                    <span className="price-right"> متری {estate.price / 1000000} م </span>
                                )}

                                {(estate.reference === 1 && estate.experts) && (
                                    <span
                                        className="price-left">{(estate.experts && estate.experts.length) ? estate.experts.map((expert, index) => (
                                            <span>{expert.name} {index + 1 < estate.experts.length ? '-' : ''}  </span>))
                                        :
                                        <span>املاک 79  </span>
                                    }
                                </span>
                                )}
                            </div>
                        </div>
                    </div>
                </Link>
                {/*{this.props.children}*/}
                {React.Children.map(this.props.children, child => {
                    console.log('child',child);
                        return React.cloneElement(child, {
                            onClick: this.remove.bind(this,estate.id)
                        })
                })}
            </div>
        )
            ;
    }
}

export default GridEstate;