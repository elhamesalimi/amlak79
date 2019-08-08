import React from 'react'
import axios from 'axios';
class ReportBug extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            showModal: false,
            estateId: undefined,
            showOtherInput: false,
            bug: null,
            another: null,
            description: null,
        }
    }

    componentDidMount() {
        this.setState({
            estateId: this.props.estateId
        });
    }

    hideModal = () => {
        this.setState({
            showModal: false,
        })
    };
changeFormInput = (e)=>{
    const name = e.target.name;
    const value = e.target.value;
    this.setState({
        [name]: value,
    })
};

    handelSubmitFormActive = (event) => {
        event.preventDefault();
        const { bug , estateId , another , description}= this.state;
        let meta ={};

        if(another !== null && another !== ''){
            meta['another'] = another;
        }
        if(description !== null && description !== ''){
            meta['description'] = description;
        }

        const params = {
            bug,
            meta,
            estate_id: estateId,
        };
        axios.post('/api/report-bug',{...params})
            .then(response=>{
                this.setState({
                    status: 'success',
                    message: 'گزارش شما با موفقیت ثبت شد.',
                });
                setTimeout(() => {
                    this.setState({
                        showModal: false,
                        message: '',
                    })
                }, 1000);
            })
            .catch(error=>{
                this.setState({
                    status: 'danger',
                    message: 'ثبت گزارش شما با مشکل  مواجه شد.',
                });
                setTimeout(() => {
                    this.setState({
                        showModal: false,
                        message: '',
                    })
                }, 2000);
            })

    };
    render() {
        return (
            <div className="bug">
                <button type="button" className="report-post" onClick={()=>{this.setState({showModal: !this.state.showModal})}}>
                    <i aria-hidden="true" className="fa fa-bullhorn"></i>گزارش
                    اشکال آگهی
                </button>
                <Modal show={this.state.showModal} handleClose={this.hideModal}>
                    <div className="panel panel-default ">
                        <div className="panel-heading">
                            گزارش اشکال در آگهی
                            <i className="fa fa-times pull-left" onClick={this.hideModal}></i>
                        </div>
                        <form onSubmit={this.handelSubmitFormActive}>
                            {this.state.message &&
                            <div className = {`alert ${this.state.status==='success' ? 'alert-success' : 'alert-danger'}`}>{this.state.message}</div>
                            }
                            <input type="hidden" name={"estate_id"} value={this.state.estateId}/>
                            <div className="panel-body">
                                <div className="radio">
                                    <label><input type="radio" name="bug" value="sell" onChange={this.changeFormInput}/>  فروش رفته است</label>
                                </div>
                                <div className="radio">
                                    <label><input type="radio" name="bug" value="price" onChange={this.changeFormInput}/>  قیمت آگهی نامناسب است.</label>
                                </div>
                                <div className="other-box">
                                <div className="radio">
                                    <label><input type="radio" name="bug"  value="other" onChange={this.changeFormInput} />  {this.state.bug==='other' ?'': 'سایر'}</label>
                                </div>
                                {this.state.bug==='other' &&
                                <div className=" col-xs-12 field placeholder mp15 pl0 pr0" >
                                    <input id="exchange_with" name="another" type="text"
                                        value={this.state.another}
                                        onChange={this.changeFormInput}
                                           className="form-control"
                                           required={true}
                                           maxLength="30" placeholder="سایر"
                                    />

                                </div>
                                }
                                </div>
                                <div className="form-group clear-both ">
                                    <label htmlFor="description">توضیحات:</label>
                                    <textarea className="form-control" rows="3" id="description" name="description" onChange={this.changeFormInput}></textarea>
                                </div>
                                <input type="submit" className="btn btn-danger pull-left" value="ارسال گزارش"/>
                                <a className="btn btn-warning pull-right "
                                        onClick={this.hideModal}>انصراف
                                </a>
                            </div>
                        </form>
                        
                    </div>
                </Modal>
            </div>
        );
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

export default ReportBug;
