import Link from 'next/link';

const Header: React.FC = () => {
    return (
        <header>
            <nav>
                <ul>
                    <li><Link href="/">ホーム</Link></li>
                    <li><Link href="/about">アバウト</Link></li>
                    <li><Link href="/users">ユーザ</Link></li>
                </ul>
            </nav>
        </header>
    );
};

export default Header;
