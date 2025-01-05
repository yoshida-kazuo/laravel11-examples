import React from 'react';
import Head from 'next/head';
import Header from '../common/Header';
import Footer from '../common/Footer';
import { LayoutProps } from '../../types/layout';

const Layout: React.FC<LayoutProps> = ({
    children,
    title = 'Default Title',
    description = 'Default description for the site.',
    keywords = 'default, keywords, site',
}) => {
    return (
        <>
            <Head>
                <title>{title}</title>
                <meta name="description" content={description} />
                <meta name="keywords" content={keywords} />
                <meta name="viewport" content="width=device-width,initial-scale=1" />
            </Head>
            <Header />
            <main>{children}</main>
            <Footer />
        </>
    );
};

export default Layout;
