import React from 'react';

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
export default Modal;