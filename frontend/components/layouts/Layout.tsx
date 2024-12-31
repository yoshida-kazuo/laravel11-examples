import React from 'react';
import Header from '../common/Header';
import Footer from '../common/Footer';

const Layout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    return (
        <>
            <Header />
            <main>{children}</main>
            <Footer />
        </>
    );
};

export default Layout;
