-- =========================================================
-- Database: school_exam_db
-- Muc tieu: He thong thi truc tuyen (admin, teacher, student)
-- Compatible: MySQL 8+ / MariaDB 10.4+ (XAMPP)
-- =========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS school_exam_db;
CREATE DATABASE school_exam_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_exam_db;

-- =========================================================
-- 1) Danh muc co ban
-- =========================================================

CREATE TABLE Khoi_lop (
    ID_KhoiLop INT AUTO_INCREMENT PRIMARY KEY,
    Ten_KhoiLop VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Mon_Hoc (
    ID_MonHoc INT AUTO_INCREMENT PRIMARY KEY,
    Ten_MonHoc VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE `User` (
    ID_User INT AUTO_INCREMENT PRIMARY KEY,
    Pass_User VARCHAR(255) NOT NULL,
    NgayTaoTaiKhoan_User DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PhanQuyen_User ENUM('admin', 'teacher', 'student') NOT NULL,
    HoVaTen_User VARCHAR(150) NOT NULL,
    TrangThaiHoatDong_User ENUM('active', 'inactive', 'locked') NOT NULL DEFAULT 'active',
    SoDienThoai_User VARCHAR(20) NULL,
    NgayThangNamSinh_User DATE NULL,
    EmailCaNhan_User VARCHAR(150) NULL UNIQUE,
    PhuTrachKhoi_User INT NULL,
    PhuTrachMon_User INT NULL,
    INDEX idx_user_role (PhanQuyen_User),
    CONSTRAINT fk_user_khoi FOREIGN KEY (PhuTrachKhoi_User) REFERENCES Khoi_lop(ID_KhoiLop),
    CONSTRAINT fk_user_mon FOREIGN KEY (PhuTrachMon_User) REFERENCES Mon_Hoc(ID_MonHoc)
) ENGINE=InnoDB;

CREATE TABLE QuanLy (
    ID_QuanLy INT AUTO_INCREMENT PRIMARY KEY,
    ID_User INT NOT NULL UNIQUE,
    CapQuanLy ENUM('super_admin', 'admin') NOT NULL DEFAULT 'admin',
    GhiChu_QuanLy VARCHAR(255) NULL,
    CONSTRAINT fk_quanly_user FOREIGN KEY (ID_User) REFERENCES `User`(ID_User)
) ENGINE=InnoDB;

CREATE TABLE Lop_hoc (
    ID_LopHoc INT AUTO_INCREMENT PRIMARY KEY,
    ID_KhoiLop INT NOT NULL,
    ID_MonHoc INT NOT NULL,
    ID_Teacher INT NOT NULL,
    ID_Student INT NULL,
    TenLopHoc VARCHAR(100) NOT NULL,
    NamHoc VARCHAR(20) NOT NULL,
    UNIQUE KEY uq_lophoc (TenLopHoc, NamHoc, ID_MonHoc),
    CONSTRAINT fk_lophoc_khoi FOREIGN KEY (ID_KhoiLop) REFERENCES Khoi_lop(ID_KhoiLop),
    CONSTRAINT fk_lophoc_mon FOREIGN KEY (ID_MonHoc) REFERENCES Mon_Hoc(ID_MonHoc),
    CONSTRAINT fk_lophoc_teacher FOREIGN KEY (ID_Teacher) REFERENCES `User`(ID_User),
    CONSTRAINT fk_lophoc_student FOREIGN KEY (ID_Student) REFERENCES `User`(ID_User)
) ENGINE=InnoDB;

-- Bo sung de phan lop nhieu-hoc-sinh (khong pha vo cau truc cu)
CREATE TABLE Lop_hoc_ThanhVien (
    ID_LopHoc INT NOT NULL,
    ID_Student INT NOT NULL,
    NgayThamGia DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ID_LopHoc, ID_Student),
    CONSTRAINT fk_lhtv_lophoc FOREIGN KEY (ID_LopHoc) REFERENCES Lop_hoc(ID_LopHoc),
    CONSTRAINT fk_lhtv_student FOREIGN KEY (ID_Student) REFERENCES `User`(ID_User)
) ENGINE=InnoDB;

CREATE TABLE Thong_bao (
    ID_ThongBao INT AUTO_INCREMENT PRIMARY KEY,
    ID_User INT NOT NULL,
    ID_KhoiLop INT NULL,
    ID_MonHoc INT NULL,
    NoiDung_ThongBao TEXT NOT NULL,
    NgayTao_ThongBao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_thongbao_user FOREIGN KEY (ID_User) REFERENCES `User`(ID_User),
    CONSTRAINT fk_thongbao_khoi FOREIGN KEY (ID_KhoiLop) REFERENCES Khoi_lop(ID_KhoiLop),
    CONSTRAINT fk_thongbao_mon FOREIGN KEY (ID_MonHoc) REFERENCES Mon_Hoc(ID_MonHoc)
) ENGINE=InnoDB;

CREATE TABLE Chu_De (
    ID_ChuDe INT AUTO_INCREMENT PRIMARY KEY,
    ID_MonHoc INT NOT NULL,
    ID_KhoiLop INT NOT NULL,
    NoiDung_ChuDe VARCHAR(255) NOT NULL,
    ID_NguoiTao INT NOT NULL,
    CONSTRAINT fk_chude_mon FOREIGN KEY (ID_MonHoc) REFERENCES Mon_Hoc(ID_MonHoc),
    CONSTRAINT fk_chude_khoi FOREIGN KEY (ID_KhoiLop) REFERENCES Khoi_lop(ID_KhoiLop),
    CONSTRAINT fk_chude_nguoitao FOREIGN KEY (ID_NguoiTao) REFERENCES `User`(ID_User)
) ENGINE=InnoDB;

CREATE TABLE Cau_hoi_trac_nghiem_4_phuong_an (
    ID_TracNghiem4PhuongAn INT AUTO_INCREMENT PRIMARY KEY,
    ID_ChuDe INT NOT NULL,
    ID_MonHoc INT NOT NULL,
    ID_KhoiLop INT NOT NULL,
    NoiDungCauHoi_TracNghiem4PhuongAn TEXT NOT NULL,
    NoiDungCauTraLoi1_TracNghiem4PhuongAn VARCHAR(255) NOT NULL,
    NoiDungCauTraLoi2_TracNghiem4PhuongAn VARCHAR(255) NOT NULL,
    NoiDungCauTraLoi3_TracNghiem4PhuongAn VARCHAR(255) NOT NULL,
    NoiDungCauTraLoi4_TracNghiem4PhuongAn VARCHAR(255) NOT NULL,
    DapAn_TracNghiem4PhuongAn CHAR(1) NOT NULL,
    HuongDanGiai_TracNghiem4PhuongAn TEXT NULL,
    CONSTRAINT fk_q4pa_chude FOREIGN KEY (ID_ChuDe) REFERENCES Chu_De(ID_ChuDe),
    CONSTRAINT fk_q4pa_mon FOREIGN KEY (ID_MonHoc) REFERENCES Mon_Hoc(ID_MonHoc),
    CONSTRAINT fk_q4pa_khoi FOREIGN KEY (ID_KhoiLop) REFERENCES Khoi_lop(ID_KhoiLop),
    CONSTRAINT ck_q4pa_dapan CHECK (DapAn_TracNghiem4PhuongAn IN ('A', 'B', 'C', 'D'))
) ENGINE=InnoDB;

CREATE TABLE Cau_hoi_trac_nghiem_dung_sai (
    ID_TracNghiemDungSai INT AUTO_INCREMENT PRIMARY KEY,
    ID_ChuDe INT NOT NULL,
    ID_MonHoc INT NOT NULL,
    ID_KhoiLop INT NOT NULL,
    NoiDungCauHoi_TracNghiemDungSai TEXT NOT NULL,
    NoiDungMenhDe1_TracNghiemDungSai VARCHAR(255) NOT NULL,
    NoiDungMenhDe2_TracNghiemDungSai VARCHAR(255) NOT NULL,
    NoiDungMenhDe3_TracNghiemDungSai VARCHAR(255) NOT NULL,
    NoiDungMenhDe4_TracNghiemDungSai VARCHAR(255) NOT NULL,
    DapAn_TracNghiem4PhuongAn VARCHAR(20) NOT NULL,
    HuongDanGiaiMenhDe1_TracNghiemDungSai TEXT NULL,
    HuongDanGiaiMenhDe2_TracNghiemDungSai TEXT NULL,
    HuongDanGiaiMenhDe3_TracNghiemDungSai TEXT NULL,
    HuongDanGiaiMenhDe4_TracNghiemDungSai TEXT NULL,
    CONSTRAINT fk_qds_chude FOREIGN KEY (ID_ChuDe) REFERENCES Chu_De(ID_ChuDe),
    CONSTRAINT fk_qds_mon FOREIGN KEY (ID_MonHoc) REFERENCES Mon_Hoc(ID_MonHoc),
    CONSTRAINT fk_qds_khoi FOREIGN KEY (ID_KhoiLop) REFERENCES Khoi_lop(ID_KhoiLop)
) ENGINE=InnoDB;

CREATE TABLE Cau_hoi_tra_loi_ngan (
    ID_TracNghiemTraLoiNgan INT AUTO_INCREMENT PRIMARY KEY,
    ID_ChuDe INT NOT NULL,
    ID_MonHoc INT NOT NULL,
    ID_KhoiLop INT NOT NULL,
    NoiDungCauHoi_TracNghiemTraLoiNgan TEXT NOT NULL,
    KiTuThu1CuaDapAn_TracNghiemTraLoiNgan CHAR(1) NOT NULL,
    KiTuThu2CuaDapAn_TracNghiemTraLoiNgan CHAR(1) NOT NULL,
    KiTuThu3CuaDapAn_TracNghiemTraLoiNgan CHAR(1) NOT NULL,
    KiTuThu4CuaDapAn_TracNghiemTraLoiNgan CHAR(1) NOT NULL,
    HuongDanGiai_TracNghiemTraLoiNgan TEXT NULL,
    CONSTRAINT fk_qngan_chude FOREIGN KEY (ID_ChuDe) REFERENCES Chu_De(ID_ChuDe),
    CONSTRAINT fk_qngan_mon FOREIGN KEY (ID_MonHoc) REFERENCES Mon_Hoc(ID_MonHoc),
    CONSTRAINT fk_qngan_khoi FOREIGN KEY (ID_KhoiLop) REFERENCES Khoi_lop(ID_KhoiLop)
) ENGINE=InnoDB;

CREATE TABLE De_Thi (
    ID_MaDeThi INT AUTO_INCREMENT PRIMARY KEY,
    TenDeThi VARCHAR(150) NOT NULL,
    ID_NguoiTao INT NOT NULL,
    ID_MaMon INT NOT NULL,
    ID_MaKhoi INT NOT NULL,
    NgayTao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    MoTa VARCHAR(255) NULL,
    CONSTRAINT fk_dethi_nguoitao FOREIGN KEY (ID_NguoiTao) REFERENCES `User`(ID_User),
    CONSTRAINT fk_dethi_mon FOREIGN KEY (ID_MaMon) REFERENCES Mon_Hoc(ID_MonHoc),
    CONSTRAINT fk_dethi_khoi FOREIGN KEY (ID_MaKhoi) REFERENCES Khoi_lop(ID_KhoiLop)
) ENGINE=InnoDB;

CREATE TABLE De_Thi_Chi_Tiet (
    ID_DeThiChiTiet INT AUTO_INCREMENT PRIMARY KEY,
    ID_MaDeThi INT NOT NULL,
    ID_NguoiTao INT NOT NULL,
    ID_MaMon INT NOT NULL,
    ID_MaKhoi INT NOT NULL,
    ID_TracNghiem4PhuongAn INT NULL,
    ID_TracNghiemDungSai INT NULL,
    ID_TracNghiemTraLoiNgan INT NULL,
    CONSTRAINT fk_detchitiet_dethi FOREIGN KEY (ID_MaDeThi) REFERENCES De_Thi(ID_MaDeThi),
    CONSTRAINT fk_detchitiet_nguoitao FOREIGN KEY (ID_NguoiTao) REFERENCES `User`(ID_User),
    CONSTRAINT fk_detchitiet_mon FOREIGN KEY (ID_MaMon) REFERENCES Mon_Hoc(ID_MonHoc),
    CONSTRAINT fk_detchitiet_khoi FOREIGN KEY (ID_MaKhoi) REFERENCES Khoi_lop(ID_KhoiLop),
    CONSTRAINT fk_detchitiet_4pa FOREIGN KEY (ID_TracNghiem4PhuongAn) REFERENCES Cau_hoi_trac_nghiem_4_phuong_an(ID_TracNghiem4PhuongAn),
    CONSTRAINT fk_detchitiet_ds FOREIGN KEY (ID_TracNghiemDungSai) REFERENCES Cau_hoi_trac_nghiem_dung_sai(ID_TracNghiemDungSai),
    CONSTRAINT fk_detchitiet_ngan FOREIGN KEY (ID_TracNghiemTraLoiNgan) REFERENCES Cau_hoi_tra_loi_ngan(ID_TracNghiemTraLoiNgan)
) ENGINE=InnoDB;

CREATE TABLE Ky_thi (
    ID_KyThi INT AUTO_INCREMENT PRIMARY KEY,
    ID_KhoiLop INT NOT NULL,
    ID_MonHoc INT NOT NULL,
    ID_ChuDe INT NOT NULL,
    ID_LopHoc INT NOT NULL,
    Ten_KyThi VARCHAR(150) NOT NULL,
    MoTa_KyThi VARCHAR(255) NULL,
    ThoiGianLamBai_KyThi INT NOT NULL,
    PhanBoDiemTracNghiem4PhuongAn_KyThi DECIMAL(5,2) NOT NULL,
    PhanBoDiemTracNghiemDungSai_KyThi DECIMAL(5,2) NOT NULL,
    PhanBoDiemTracNghiemTraLoiNgan_KyThi DECIMAL(5,2) NOT NULL,
    SoCauHoiTracNghiem4PhuongAn_KyThi INT NOT NULL,
    SoCauHoiTracNghiemDungSai_KyThi INT NOT NULL,
    SoCauHoiTracNghiemTraLoiNgan_KyThi INT NOT NULL,
    ID_MaDeThi INT NOT NULL,
    ThoiGianBatDau_KyThi DATETIME NULL,
    ThoiGianKetThuc_KyThi DATETIME NULL,
    CONSTRAINT fk_kythi_khoi FOREIGN KEY (ID_KhoiLop) REFERENCES Khoi_lop(ID_KhoiLop),
    CONSTRAINT fk_kythi_mon FOREIGN KEY (ID_MonHoc) REFERENCES Mon_Hoc(ID_MonHoc),
    CONSTRAINT fk_kythi_chude FOREIGN KEY (ID_ChuDe) REFERENCES Chu_De(ID_ChuDe),
    CONSTRAINT fk_kythi_lophoc FOREIGN KEY (ID_LopHoc) REFERENCES Lop_hoc(ID_LopHoc),
    CONSTRAINT fk_kythi_dethi FOREIGN KEY (ID_MaDeThi) REFERENCES De_Thi(ID_MaDeThi)
) ENGINE=InnoDB;

CREATE TABLE Diem_danh (
    ID_DiemDanh INT AUTO_INCREMENT PRIMARY KEY,
    ID_LopHoc INT NOT NULL,
    NgayHoc_DiemDanh DATE NOT NULL,
    ThoiGianBatDau_DiemDanh DATETIME NOT NULL,
    ThoiGianKetThuc_DiemDanh DATETIME NULL,
    ChiTietDiemDanh_DiemDanh TEXT NULL,
    TrangThaiBuoiHoc_DiemDanh ENUM('scheduled', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'scheduled',
    CONSTRAINT fk_diemdanh_lophoc FOREIGN KEY (ID_LopHoc) REFERENCES Lop_hoc(ID_LopHoc)
) ENGINE=InnoDB;

CREATE TABLE Don_xin_nghi (
    ID_DonXinNghi INT AUTO_INCREMENT PRIMARY KEY,
    ID_LopHoc INT NOT NULL,
    ID_User INT NOT NULL,
    ID_DiemDanh INT NOT NULL,
    ThoiGianGui_DonXinNghi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NoiDung_DonXinNghi TEXT NOT NULL,
    TrangThai_DonXinNghi ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    CONSTRAINT fk_donnghi_lophoc FOREIGN KEY (ID_LopHoc) REFERENCES Lop_hoc(ID_LopHoc),
    CONSTRAINT fk_donnghi_user FOREIGN KEY (ID_User) REFERENCES `User`(ID_User),
    CONSTRAINT fk_donnghi_diemdanh FOREIGN KEY (ID_DiemDanh) REFERENCES Diem_danh(ID_DiemDanh)
) ENGINE=InnoDB;

CREATE TABLE Diem_so (
    ID_DiemSo INT AUTO_INCREMENT PRIMARY KEY,
    ID_User INT NOT NULL,
    ID_MaKyThi INT NOT NULL,
    ID_MaDeThi INT NOT NULL,
    DiemPhanTracNghiem4PhuongAn_DiemSo DECIMAL(5,2) NOT NULL,
    DiemPhanTracNghiemDungSai_DiemSo DECIMAL(5,2) NOT NULL,
    DiemPhanTracNghiemTraLoiNgan_DiemSo DECIMAL(5,2) NOT NULL,
    TongDiem_DiemSo DECIMAL(5,2) NOT NULL,
    ThoiGianBatDau_DiemSo DATETIME NOT NULL,
    ThoiGianKetThuc_DiemSo DATETIME NOT NULL,
    ThoiGianLamBai_DiemSo INT NOT NULL,
    LichSuLamBai TEXT NULL,
    CONSTRAINT fk_diemso_user FOREIGN KEY (ID_User) REFERENCES `User`(ID_User),
    CONSTRAINT fk_diemso_kythi FOREIGN KEY (ID_MaKyThi) REFERENCES Ky_thi(ID_KyThi),
    CONSTRAINT fk_diemso_dethi FOREIGN KEY (ID_MaDeThi) REFERENCES De_Thi(ID_MaDeThi)
) ENGINE=InnoDB;

-- =========================================================
-- 2) Seed data mau
-- =========================================================

INSERT INTO Khoi_lop (Ten_KhoiLop)
VALUES ('Khối 1'), ('Khối 2'), ('Khối 3'), ('Khối 4'), ('Khối 5'), ('Khối 6'), ('Khối 7'), ('Khối 8'), ('Khối 9'), ('Khối 10'), ('Khối 11'), ('Khối 12');

INSERT INTO Mon_Hoc (Ten_MonHoc)
VALUES ('Toán học'), ('Ngữ Văn'), ('Tiếng Anh'), ('Vật lý'), ('Hóa học'), ('Sinh học'), ('Lịch sử'), ('Địa lý'), ('Giáo dục công dân'), ('Tin học');

-- Tai khoan mau dang nhap nhanh
-- admin/admin123 | giaovien/gv123 | hocsinh/hs123
-- Pass_User dung MD5 (thay bang bcrypt neu he thong dung password_hash)

INSERT INTO `User` (Pass_User, PhanQuyen_User, HoVaTen_User, TrangThaiHoatDong_User, SoDienThoai_User, NgayThangNamSinh_User, EmailCaNhan_User, PhuTrachKhoi_User, PhuTrachMon_User)
VALUES
-- 1 Admin (ID_User = 1)
(MD5('admin123'), 'admin', 'Nguyễn Văn An',     'active', '0901000001', '1980-05-10', 'admin@school.edu.vn',      NULL, NULL),

-- 7 Teacher (ID_User = 2..8)
-- PhuTrachKhoi: 10=Khối 10, 11=Khối 11, 12=Khối 12, 9=Khối 9, 8=Khối 8
-- PhuTrachMon:  1=Toán, 2=Văn, 3=Anh, 4=Lý, 5=Hóa, 6=Sinh, 7=Sử
(MD5('gv123'),    'teacher', 'Trần Thị Bình',   'active', '0902000001', '1985-03-15', 'binh.tran@school.edu.vn',  10, 1),
(MD5('gv123'),    'teacher', 'Lê Văn Cường',    'active', '0902000002', '1983-07-22', 'cuong.le@school.edu.vn',   11, 2),
(MD5('gv123'),    'teacher', 'Phạm Thị Dung',   'active', '0902000003', '1990-11-08', 'dung.pham@school.edu.vn',  12, 3),
(MD5('gv123'),    'teacher', 'Hoàng Văn Em',    'active', '0902000004', '1982-01-30', 'em.hoang@school.edu.vn',   10, 4),
(MD5('gv123'),    'teacher', 'Đặng Thị Phượng', 'active', '0902000005', '1988-09-14', 'phuong.dang@school.edu.vn',11, 5),
(MD5('gv123'),    'teacher', 'Vũ Thị Giang',    'active', '0902000006', '1991-06-25', 'giang.vu@school.edu.vn',   9,  6),
(MD5('gv123'),    'teacher', 'Ngô Văn Hải',     'active', '0902000007', '1979-12-03', 'hai.ngo@school.edu.vn',    8,  7),
-- 100 Hoc sinh (ID_User = 9..108) | mat khau: hs123
-- Nguyen (1-20)
(MD5('hs123'), 'student', 'Nguyễn Văn An',      'active', NULL, '2008-03-15', 'hocsinh001@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Bình',    'active', NULL, '2009-07-22', 'hocsinh002@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Minh Châu',   'active', NULL, '2007-11-08', 'hocsinh003@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Văn Dũng',    'active', NULL, '2010-01-30', 'hocsinh004@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Dung',    'active', NULL, '2008-09-14', 'hocsinh005@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Văn Đức',     'active', NULL, '2009-06-25', 'hocsinh006@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Hoa',     'active', NULL, '2007-12-03', 'hocsinh007@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Văn Hùng',    'active', NULL, '2010-04-18', 'hocsinh008@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Hương',   'active', NULL, '2008-08-27', 'hocsinh009@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Minh Khoa',   'active', NULL, '2009-02-11', 'hocsinh010@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Lan',     'active', NULL, '2007-05-20', 'hocsinh011@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Văn Long',    'active', NULL, '2010-10-05', 'hocsinh012@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Linh',    'active', NULL, '2008-07-16', 'hocsinh013@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Văn Minh',    'active', NULL, '2009-03-29', 'hocsinh014@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Mai',     'active', NULL, '2007-01-07', 'hocsinh015@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Văn Nam',     'active', NULL, '2010-11-23', 'hocsinh016@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Ngọc',   'active', NULL, '2008-06-12', 'hocsinh017@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Văn Phong',   'active', NULL, '2009-09-08', 'hocsinh018@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Thị Phương',  'active', NULL, '2007-04-30', 'hocsinh019@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Nguyễn Văn Quân',    'active', NULL, '2010-08-15', 'hocsinh020@school.edu.vn', NULL, NULL),
-- Tran (21-35)
(MD5('hs123'), 'student', 'Trần Văn Sơn',       'active', NULL, '2008-02-19', 'hocsinh021@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Thị Thu',       'active', NULL, '2009-10-04', 'hocsinh022@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Minh Tùng',     'active', NULL, '2007-08-21', 'hocsinh023@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Thị Trang',     'active', NULL, '2010-12-09', 'hocsinh024@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Văn Việt',      'active', NULL, '2008-05-03', 'hocsinh025@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Thị Xuân',      'active', NULL, '2009-01-17', 'hocsinh026@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Văn Huy',       'active', NULL, '2007-07-06', 'hocsinh027@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Thị Yến',       'active', NULL, '2010-03-25', 'hocsinh028@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Quốc Bảo',      'active', NULL, '2008-11-14', 'hocsinh029@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Thị Hằng',      'active', NULL, '2009-04-28', 'hocsinh030@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Văn Đạt',       'active', NULL, '2007-10-11', 'hocsinh031@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Thị Ly',        'active', NULL, '2010-06-30', 'hocsinh032@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Minh Trí',      'active', NULL, '2008-01-22', 'hocsinh033@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Thị Nhi',       'active', NULL, '2009-08-07', 'hocsinh034@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Trần Văn Lâm',       'active', NULL, '2007-03-16', 'hocsinh035@school.edu.vn', NULL, NULL),
-- Le (36-47)
(MD5('hs123'), 'student', 'Lê Văn Nhân',        'active', NULL, '2010-09-01', 'hocsinh036@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Thị Trâm',        'active', NULL, '2008-04-10', 'hocsinh037@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Minh An',         'active', NULL, '2009-12-24', 'hocsinh038@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Thị Vy',          'active', NULL, '2007-06-13', 'hocsinh039@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Văn Bảo',         'active', NULL, '2010-02-28', 'hocsinh040@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Thị Châu',        'active', NULL, '2008-10-17', 'hocsinh041@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Văn Đức',         'active', NULL, '2009-05-05', 'hocsinh042@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Thị Hoa',         'active', NULL, '2007-09-19', 'hocsinh043@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Văn Khoa',        'active', NULL, '2010-07-08', 'hocsinh044@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Thị Linh',        'active', NULL, '2008-12-31', 'hocsinh045@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Minh Long',       'active', NULL, '2009-06-20', 'hocsinh046@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Lê Thị Ngọc',        'active', NULL, '2007-02-14', 'hocsinh047@school.edu.vn', NULL, NULL),
-- Pham (48-57)
(MD5('hs123'), 'student', 'Phạm Văn Phong',     'active', NULL, '2010-11-03', 'hocsinh048@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Thị Thu',       'active', NULL, '2008-08-22', 'hocsinh049@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Minh Tuấn',     'active', NULL, '2009-03-11', 'hocsinh050@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Thị Trang',     'active', NULL, '2007-07-27', 'hocsinh051@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Văn Việt',      'active', NULL, '2010-04-16', 'hocsinh052@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Thị Xuân',      'active', NULL, '2008-01-05', 'hocsinh053@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Quốc Huy',      'active', NULL, '2009-10-30', 'hocsinh054@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Thị Yến',       'active', NULL, '2007-05-09', 'hocsinh055@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Văn Đạt',       'active', NULL, '2010-09-18', 'hocsinh056@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phạm Thị Hằng',      'active', NULL, '2008-06-07', 'hocsinh057@school.edu.vn', NULL, NULL),
-- Hoang (58-65)
(MD5('hs123'), 'student', 'Hoàng Văn Minh',     'active', NULL, '2009-02-23', 'hocsinh058@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Hoàng Thị Mai',      'active', NULL, '2007-08-12', 'hocsinh059@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Hoàng Văn Nam',      'active', NULL, '2010-12-01', 'hocsinh060@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Hoàng Thị Lan',      'active', NULL, '2008-03-20', 'hocsinh061@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Hoàng Minh Quân',    'active', NULL, '2009-07-09', 'hocsinh062@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Hoàng Thị Phương',   'active', NULL, '2007-11-28', 'hocsinh063@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Hoàng Văn Sơn',      'active', NULL, '2010-05-17', 'hocsinh064@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Hoàng Thị Hương',    'active', NULL, '2008-09-06', 'hocsinh065@school.edu.vn', NULL, NULL),
-- Huynh (66-72)
(MD5('hs123'), 'student', 'Huỳnh Văn Tùng',     'active', NULL, '2009-04-15', 'hocsinh066@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Huỳnh Thị Linh',     'active', NULL, '2007-10-04', 'hocsinh067@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Huỳnh Văn Việt',     'active', NULL, '2010-08-23', 'hocsinh068@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Huỳnh Thị Nhi',      'active', NULL, '2008-02-12', 'hocsinh069@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Huỳnh Minh Khoa',    'active', NULL, '2009-06-01', 'hocsinh070@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Huỳnh Thị Vy',       'active', NULL, '2007-12-20', 'hocsinh071@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Huỳnh Văn Huy',      'active', NULL, '2010-10-09', 'hocsinh072@school.edu.vn', NULL, NULL),
-- Phan (73-78)
(MD5('hs123'), 'student', 'Phan Văn Đức',       'active', NULL, '2008-07-28', 'hocsinh073@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phan Thị Trâm',      'active', NULL, '2009-11-17', 'hocsinh074@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phan Minh Long',     'active', NULL, '2007-04-06', 'hocsinh075@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phan Thị Châu',      'active', NULL, '2010-01-25', 'hocsinh076@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phan Văn Bảo',       'active', NULL, '2008-05-14', 'hocsinh077@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Phan Thị Ngọc',      'active', NULL, '2009-09-03', 'hocsinh078@school.edu.vn', NULL, NULL),
-- Vu (79-83)
(MD5('hs123'), 'student', 'Vũ Văn An',          'active', NULL, '2007-06-22', 'hocsinh079@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Vũ Thị Bình',        'active', NULL, '2010-04-11', 'hocsinh080@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Vũ Minh Cường',      'active', NULL, '2008-12-30', 'hocsinh081@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Vũ Thị Dung',        'active', NULL, '2009-08-19', 'hocsinh082@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Vũ Văn Hải',         'active', NULL, '2007-02-08', 'hocsinh083@school.edu.vn', NULL, NULL),
-- Vo (84-88)
(MD5('hs123'), 'student', 'Võ Thị Hoa',         'active', NULL, '2010-11-27', 'hocsinh084@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Võ Văn Minh',        'active', NULL, '2008-03-16', 'hocsinh085@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Võ Thị Thu',         'active', NULL, '2009-07-05', 'hocsinh086@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Võ Văn Trí',         'active', NULL, '2007-09-24', 'hocsinh087@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Võ Thị Xuân',        'active', NULL, '2010-01-13', 'hocsinh088@school.edu.vn', NULL, NULL),
-- Dang (89-92)
(MD5('hs123'), 'student', 'Đặng Văn Quân',      'active', NULL, '2008-10-02', 'hocsinh089@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Đặng Thị Hằng',      'active', NULL, '2009-02-21', 'hocsinh090@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Đặng Minh Phong',    'active', NULL, '2007-08-10', 'hocsinh091@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Đặng Thị Trang',     'active', NULL, '2010-06-29', 'hocsinh092@school.edu.vn', NULL, NULL),
-- Bui (93-96)
(MD5('hs123'), 'student', 'Bùi Văn Đạt',        'active', NULL, '2008-04-18', 'hocsinh093@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Bùi Thị Ly',         'active', NULL, '2009-12-07', 'hocsinh094@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Bùi Văn Hùng',       'active', NULL, '2007-03-26', 'hocsinh095@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Bùi Thị Yến',        'active', NULL, '2010-09-15', 'hocsinh096@school.edu.vn', NULL, NULL),
-- Do (97-98)
(MD5('hs123'), 'student', 'Đỗ Văn Sơn',         'active', NULL, '2008-07-04', 'hocsinh097@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Đỗ Thị Linh',        'active', NULL, '2009-11-23', 'hocsinh098@school.edu.vn', NULL, NULL),
-- Ho (99-100)
(MD5('hs123'), 'student', 'Hồ Văn Nhân',        'active', NULL, '2007-05-12', 'hocsinh099@school.edu.vn', NULL, NULL),
(MD5('hs123'), 'student', 'Hồ Thị Nhi',         'active', NULL, '2010-03-01', 'hocsinh100@school.edu.vn', NULL, NULL);
-- =========================================================
-- QuanLy: cap phan quyen cho admin (ID_User = 1)
-- =========================================================
INSERT INTO QuanLy (ID_User, CapQuanLy, GhiChu_QuanLy)
VALUES (1, 'super_admin', 'Quản trị viên hệ thống');

-- =========================================================
-- Lop_hoc: 7 lop, moi GV 1 lop, nam hoc 2024-2025
-- ID_KhoiLop theo GV: GV2=K10, GV3=K11, GV4=K12, GV5=K10, GV6=K11, GV7=K9, GV8=K8
-- ID_MonHoc theo GV:  GV2=1(Toan), GV3=2(Van), GV4=3(Anh), GV5=4(Ly), GV6=5(Hoa), GV7=6(Sinh), GV8=7(Su)
-- =========================================================
INSERT INTO Lop_hoc (ID_KhoiLop, ID_MonHoc, ID_Teacher, ID_Student, TenLopHoc, NamHoc)
VALUES
(10, 1, 2, NULL, 'Lớp 10A1', '2024-2025'),
(11, 2, 3, NULL, 'Lớp 11A1', '2024-2025'),
(12, 3, 4, NULL, 'Lớp 12A1', '2024-2025'),
(10, 4, 5, NULL, 'Lớp 10A2', '2024-2025'),
(11, 5, 6, NULL, 'Lớp 11A2', '2024-2025'),
(9,  6, 7, NULL, 'Lớp 9A1',  '2024-2025'),
(8,  7, 8, NULL, 'Lớp 8A1',  '2024-2025');

-- =========================================================
-- Lop_hoc_ThanhVien: 100 HS (ID 9..108) chia deu 7 lop (~14-15 HS/lop)
-- Lop 1=10A1: HS 9..22  (14 HS)
-- Lop 2=11A1: HS 23..36 (14 HS)
-- Lop 3=12A1: HS 37..50 (14 HS)
-- Lop 4=10A2: HS 51..64 (14 HS)
-- Lop 5=11A2: HS 65..78 (14 HS)
-- Lop 6=9A1:  HS 79..93 (15 HS)
-- Lop 7=8A1:  HS 94..108(15 HS)
-- =========================================================
INSERT INTO Lop_hoc_ThanhVien (ID_LopHoc, ID_Student)
VALUES
-- Lop 1: 10A1 - Toan - GV Tran Thi Binh
(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),
-- Lop 2: 11A1 - Ngu Van - GV Le Van Cuong
(2,23),(2,24),(2,25),(2,26),(2,27),(2,28),(2,29),(2,30),(2,31),(2,32),(2,33),(2,34),(2,35),(2,36),
-- Lop 3: 12A1 - Tieng Anh - GV Pham Thi Dung
(3,37),(3,38),(3,39),(3,40),(3,41),(3,42),(3,43),(3,44),(3,45),(3,46),(3,47),(3,48),(3,49),(3,50),
-- Lop 4: 10A2 - Vat ly - GV Hoang Van Em
(4,51),(4,52),(4,53),(4,54),(4,55),(4,56),(4,57),(4,58),(4,59),(4,60),(4,61),(4,62),(4,63),(4,64),
-- Lop 5: 11A2 - Hoa hoc - GV Dang Thi Phuong
(5,65),(5,66),(5,67),(5,68),(5,69),(5,70),(5,71),(5,72),(5,73),(5,74),(5,75),(5,76),(5,77),(5,78),
-- Lop 6: 9A1  - Sinh hoc - GV Vu Thi Giang
(6,79),(6,80),(6,81),(6,82),(6,83),(6,84),(6,85),(6,86),(6,87),(6,88),(6,89),(6,90),(6,91),(6,92),(6,93),
-- Lop 7: 8A1  - Lich su  - GV Ngo Van Hai
(7,94),(7,95),(7,96),(7,97),(7,98),(7,99),(7,100),(7,101),(7,102),(7,103),(7,104),(7,105),(7,106),(7,107),(7,108);

-- =========================================================
-- Chu_De: theo chuong trinh SGK - ~5-8 chu de / (mon + khoi)
-- ID_MonHoc: 1=Toan, 2=Van, 3=Anh, 4=Ly, 5=Hoa, 6=Sinh, 7=Su
-- ID_KhoiLop: 8=K8, 9=K9, 10=K10, 11=K11, 12=K12
-- ID_NguoiTao: GV tuong ung (2..8)
-- =========================================================
INSERT INTO Chu_De (ID_MonHoc, ID_KhoiLop, NoiDung_ChuDe, ID_NguoiTao)
VALUES
-- -------------------------------------------------------
-- Toan 10 (8 chu de) | ID_MonHoc=1, ID_KhoiLop=10, GV=2
-- -------------------------------------------------------
(1, 10, 'Mệnh đề và tập hợp',                                  2),
(1, 10, 'Hàm số - Đồ thị hàm số bậc nhất và bậc hai',         2),
(1, 10, 'Phương trình và bất phương trình bậc nhất, bậc hai',  2),
(1, 10, 'Hệ phương trình',                                     2),
(1, 10, 'Vectơ trong mặt phẳng',                               2),
(1, 10, 'Tích vô hướng của hai vectơ và ứng dụng',            2),
(1, 10, 'Phương pháp tọa độ trong mặt phẳng',                 2),
(1, 10, 'Thống kê và xác suất',                                2),
-- -------------------------------------------------------
-- Ngu Van 11 (7 chu de) | ID_MonHoc=2, ID_KhoiLop=11, GV=3
-- -------------------------------------------------------
(2, 11, 'Thơ chữ Nôm và thơ chữ Hán trung đại',              3),
(2, 11, 'Văn xuôi lãng mạn Việt Nam (1930-1945)',             3),
(2, 11, 'Văn xuôi hiện thực phê phán (1930-1945)',            3),
(2, 11, 'Thơ mới (1932-1945)',                                 3),
(2, 11, 'Văn tế - Truyện thơ Nôm',                            3),
(2, 11, 'Nghị luận xã hội và nghị luận văn học',              3),
(2, 11, 'Kịch và nghị luận đầu thế kỷ XX',                   3),
-- -------------------------------------------------------
-- Tieng Anh 12 (8 chu de) | ID_MonHoc=3, ID_KhoiLop=12, GV=4
-- -------------------------------------------------------
(3, 12, 'Unit 1: Life Stories',                                4),
(3, 12, 'Unit 2: Urbanisation',                                4),
(3, 12, 'Unit 3: The Green Movement',                         4),
(3, 12, 'Unit 4: The Mass Media',                              4),
(3, 12, 'Unit 5: Cultural Identity',                           4),
(3, 12, 'Unit 6: Future Jobs',                                 4),
(3, 12, 'Unit 7: Economic Reforms',                            4),
(3, 12, 'Unit 8: International Integration',                   4),
-- -------------------------------------------------------
-- Vat ly 10 (7 chu de) | ID_MonHoc=4, ID_KhoiLop=10, GV=5
-- -------------------------------------------------------
(4, 10, 'Động học chất điểm',                                  5),
(4, 10, 'Động lực học chất điểm',                              5),
(4, 10, 'Cân bằng và chuyển động của vật rắn',                5),
(4, 10, 'Các định luật bảo toàn',                              5),
(4, 10, 'Chất khí - Định luật chất khí',                      5),
(4, 10, 'Cơ sở của nhiệt động lực học',                       5),
(4, 10, 'Chất lỏng - Chất rắn - Sự chuyển thể',              5),
-- -------------------------------------------------------
-- Hoa hoc 11 (8 chu de) | ID_MonHoc=5, ID_KhoiLop=11, GV=6
-- -------------------------------------------------------
(5, 11, 'Sự điện li',                                          6),
(5, 11, 'Nitơ và hợp chất của Nitơ',                          6),
(5, 11, 'Photpho và hợp chất của Photpho',                    6),
(5, 11, 'Cacbon - Silic và hợp chất',                         6),
(5, 11, 'Đại cương về hóa học hữu cơ',                        6),
(5, 11, 'Hiđrocacbon no (Ankan)',                              6),
(5, 11, 'Hiđrocacbon không no (Anken - Ankadien - Ankin)',    6),
(5, 11, 'Ancol - Phenol - Anđehit - Axit cacboxylic',        6),
-- -------------------------------------------------------
-- Sinh hoc 9 (8 chu de) | ID_MonHoc=6, ID_KhoiLop=9, GV=7
-- -------------------------------------------------------
(6, 9,  'Di truyền và biến dị - Các thí nghiệm của Menđen',  7),
(6, 9,  'Nhiễm sắc thể và di truyền liên kết',               7),
(6, 9,  'ADN - Gen - Mã di truyền và quá trình tổng hợp Protein', 7),
(6, 9,  'Biến dị - Đột biến gen và đột biến NST',            7),
(6, 9,  'Di truyền học người',                                 7),
(6, 9,  'Ứng dụng di truyền học vào chọn giống',              7),
(6, 9,  'Sinh vật và môi trường - Các nhân tố sinh thái',    7),
(6, 9,  'Hệ sinh thái - Cân bằng sinh học và bảo vệ môi trường', 7),
-- -------------------------------------------------------
-- Lich su 8 (8 chu de) | ID_MonHoc=7, ID_KhoiLop=8, GV=8
-- -------------------------------------------------------
(7, 8,  'Châu Âu và nước Mỹ cuối thế kỷ XVIII - đầu thế kỷ XX', 8),
(7, 8,  'Các nước Á - Phi - Mỹ Latinh cuối thế kỷ XIX - đầu thế kỷ XX', 8),
(7, 8,  'Chiến tranh thế giới thứ nhất (1914-1918)',          8),
(7, 8,  'Cách mạng tháng Mười Nga 1917 và Liên Xô (1917-1941)', 8),
(7, 8,  'Các nước tư bản giữa hai cuộc chiến tranh thế giới (1919-1939)', 8),
(7, 8,  'Chiến tranh thế giới thứ hai (1939-1945)',           8),
(7, 8,  'Việt Nam từ năm 1858 đến cuối thế kỷ XIX',          8),
(7, 8,  'Việt Nam từ đầu thế kỷ XX đến năm 1918',            8);

-- =========================================================
-- Cau_hoi_trac_nghiem_4_phuong_an: 3 cau / chu de = 162 cau
-- (ID_ChuDe, ID_MonHoc, ID_KhoiLop, NoiDungCauHoi, A, B, C, D, DapAn, HuongDanGiai)
-- =========================================================
INSERT INTO Cau_hoi_trac_nghiem_4_phuong_an
(ID_ChuDe, ID_MonHoc, ID_KhoiLop,
 NoiDungCauHoi_TracNghiem4PhuongAn,
 NoiDungCauTraLoi1_TracNghiem4PhuongAn,
 NoiDungCauTraLoi2_TracNghiem4PhuongAn,
 NoiDungCauTraLoi3_TracNghiem4PhuongAn,
 NoiDungCauTraLoi4_TracNghiem4PhuongAn,
 DapAn_TracNghiem4PhuongAn,
 HuongDanGiai_TracNghiem4PhuongAn)
VALUES
-- ===================== TOAN 10 (ChuDe 1-8) =====================
-- ChuDe 1: Menh de va tap hop
(1,1,10,'Mệnh đề nào sau đây là đúng?','2 + 2 = 5','√4 = 2','3 là số chẵn','π là số hữu tỉ','B','√4 = 2 là đúng vì 2² = 4'),
(1,1,10,'Cho A = {1,2,3,4} và B = {3,4,5,6}. Tập A ∩ B là:','{1,2,3,4,5,6}','{3,4}','{1,2}','{5,6}','B','Giao của hai tập là các phần tử thuộc cả hai tập'),
(1,1,10,'Phủ định của mệnh đề "Mọi số tự nhiên đều là số nguyên" là:','Không có số tự nhiên nào là số nguyên','Có ít nhất một số tự nhiên không là số nguyên','Mọi số nguyên đều là số tự nhiên','Có ít nhất một số nguyên không là số tự nhiên','B','Phủ định của "Mọi x, P(x)" là "Tồn tại x, không P(x)"'),
-- ChuDe 2: Ham so
(2,1,10,'Hàm số y = x² - 4x + 3 có đỉnh parabol tại điểm:','(2, -1)','(-2, 1)','(2, 1)','(-2, -1)','A','Đỉnh: x = -b/2a = 4/2 = 2; y = 4 - 8 + 3 = -1'),
(2,1,10,'Hàm số y = 2x + 3 đồng biến trên:','(-∞; 0)','(0; +∞)','(-∞; +∞)','(-3; +∞)','C','Hàm bậc nhất hệ số a = 2 > 0 nên đồng biến trên toàn trục số'),
(2,1,10,'Parabol y = x² - 2x - 3 cắt trục Ox tại các điểm có hoành độ:','x = 1 và x = -3','x = -1 và x = 3','x = 1 và x = 3','x = -1 và x = -3','B','x² - 2x - 3 = 0 => (x+1)(x-3) = 0 => x = -1 hoặc x = 3'),
-- ChuDe 3: Phuong trinh va bat phuong trinh
(3,1,10,'Phương trình x² - 5x + 6 = 0 có nghiệm là:','x = 1 và x = 6','x = 2 và x = 3','x = -2 và x = -3','x = 1 và x = -6','B','(x-2)(x-3) = 0'),
(3,1,10,'Bất phương trình 2x - 4 > 0 có tập nghiệm là:','x < 2','x > 2','x < -2','x > -2','B','2x > 4 => x > 2'),
(3,1,10,'Phương trình 2x² + 3x - 2 = 0 có tổng hai nghiệm là:','3/2','-3/2','1','-1','B','Theo Viète: x1 + x2 = -b/a = -3/2'),
-- ChuDe 4: He phuong trinh
(4,1,10,'Nghiệm của hệ {x + y = 5 ; x - y = 1} là:','(3, 2)','(2, 3)','(4, 1)','(1, 4)','A','Cộng: 2x = 6 => x = 3; y = 2'),
(4,1,10,'Hệ {2x + y = 7 ; x - y = 2} có nghiệm là:','(1, 5)','(3, 1)','(2, 3)','(4, -1)','B','Cộng: 3x = 9 => x = 3; y = 1'),
(4,1,10,'Hệ {3x + 2y = 12 ; x + y = 5} có nghiệm là:','(1, 4)','(2, 3)','(3, 2)','(4, 1)','B','Từ pt2: x = 5-y; thay vào pt1: 3(5-y)+2y=12 => y=3; x=2'),
-- ChuDe 5: Vecto
(5,1,10,'Cho vectơ a⃗ = (3, 4). Độ dài của vectơ a⃗ là:','7','5','12','25','B','|a| = √(3²+4²) = √25 = 5'),
(5,1,10,'Cho A(1, 2) và B(4, 6). Vectơ AB⃗ có tọa độ là:','(3, 4)','(-3, -4)','(5, 8)','(-5, -8)','A','AB⃗ = (4-1, 6-2) = (3, 4)'),
(5,1,10,'Hai vectơ a⃗ = (1, 2) và b⃗ = (2, 4) có quan hệ:','Vuông góc','Cùng phương','Bằng nhau','Đối nhau','B','b⃗ = 2·a⃗ nên cùng phương'),
-- ChuDe 6: Tich vo huong
(6,1,10,'Tích vô hướng a⃗·b⃗ với a⃗=(2,1), b⃗=(3,-2) là:','8','4','6','2','B','2×3 + 1×(-2) = 6 - 2 = 4'),
(6,1,10,'Tích vô hướng a⃗·b⃗ với a⃗=(1,2), b⃗=(-2,1) bằng:','0','-4','4','2','A','1×(-2) + 2×1 = 0 => hai vectơ vuông góc'),
(6,1,10,'Góc giữa hai vectơ a⃗=(1,0) và b⃗=(0,1) là:','0°','45°','90°','180°','C','a⃗·b⃗ = 0 => góc 90°'),
-- ChuDe 7: Phuong phap toa do
(7,1,10,'Phương trình đường thẳng qua A(1,2) có hệ số góc k=3:','y = 3x - 1','y = 3x + 1','y = 3x + 2','y = 3x - 2','A','y - 2 = 3(x - 1) => y = 3x - 1'),
(7,1,10,'Phương trình đường thẳng qua A(0,2) và B(1,0) là:','2x + y - 2 = 0','x + 2y - 2 = 0','2x - y + 2 = 0','x - 2y + 4 = 0','A','Hệ số góc = (0-2)/(1-0) = -2; y = -2x + 2 => 2x + y - 2 = 0'),
(7,1,10,'Hai đường thẳng y = 2x+1 và y = 2x-3 có quan hệ:','Cắt nhau','Trùng nhau','Song song','Vuông góc','C','Cùng hệ số góc k=2, khác tung độ gốc => song song'),
-- ChuDe 8: Thong ke va xac suat
(8,1,10,'Tung một đồng xu cân đối 2 lần. Xác suất để cả hai lần ra mặt ngửa là:','1/2','1/4','1/3','2/3','B','P = 1/2 × 1/2 = 1/4'),
(8,1,10,'Từ nhóm 5 bạn, chọn ngẫu nhiên 2 bạn làm đại diện. Số cách chọn là:','10','20','15','5','A','C(5,2) = 5!/(2!×3!) = 10'),
(8,1,10,'Giá trị trung bình của dãy số: 2, 4, 6, 8, 10 là:','5','6','4','8','B','(2+4+6+8+10)/5 = 30/5 = 6'),
-- ===================== NGU VAN 11 (ChuDe 9-15) =====================
-- ChuDe 9: Tho chu Nom va chu Han
(9,2,11,'Bài thơ "Tự tình II" của Hồ Xuân Hương được viết theo thể thơ nào?','Thất ngôn tứ tuyệt','Thất ngôn bát cú Đường luật','Lục bát','Song thất lục bát','B',NULL),
(9,2,11,'Trong bài "Câu cá mùa thu", Nguyễn Khuyến miêu tả mặt ao bằng từ nào?','Xanh biếc','Trong veo','Lăn tăn','Gợn sóng','B',NULL),
(9,2,11,'Bài thơ "Thương vợ" của Tú Xương ca ngợi phẩm chất nào của bà Tú?','Thông minh, học giỏi','Tần tảo, đảm đang, hi sinh vì chồng con','Hiền lành, thùy mị','Dũng cảm, kiên cường','B',NULL),
-- ChuDe 10: Van xuoi lang man
(10,2,11,'Tác phẩm "Chữ người tử tù" của Nguyễn Tuân thuộc thể loại nào?','Tiểu thuyết','Truyện ngắn','Ký','Tùy bút','B',NULL),
(10,2,11,'Trong "Chữ người tử tù", Huấn Cao cho chữ viên quản ngục ở đâu?','Phòng khách sang trọng','Nơi pháp trường','Trong buồng tối chật hẹp của nhà tù','Tại nhà riêng quản ngục','C',NULL),
(10,2,11,'Tác phẩm "Hai đứa trẻ" của Thạch Lam thể hiện tâm trạng chủ yếu nào?','Vui tươi, phấn khởi','Buồn man mác trước cuộc sống nghèo nàn, tù túng','Căm phẫn trước bất công','Lo lắng, hồi hộp','B',NULL),
-- ChuDe 11: Van xuoi hien thuc
(11,2,11,'Nhân vật Chí Phèo bị tha hóa chủ yếu do:','Bản thân lười biếng, sa đọa','Xã hội thực dân phong kiến đẩy người nông dân vào đường cùng','Ảnh hưởng xấu từ bạn bè','Hoàn cảnh gia đình khó khăn','B',NULL),
(11,2,11,'Ai là người thức tỉnh bản chất lương thiện trong Chí Phèo?','Bà Ba','Thị Nở','Bá Kiến','Lý Cường','B',NULL),
(11,2,11,'Tác phẩm nào của Nam Cao viết về đề tài người trí thức nghèo?','Chí Phèo','Lão Hạc','Đời thừa','Tắt đèn','C',NULL),
-- ChuDe 12: Tho moi
(12,2,11,'Bài thơ "Vội vàng" của Xuân Diệu thể hiện quan niệm:','Hãy sống chậm lại để tận hưởng','Hãy sống gấp, tận hưởng tuổi thanh xuân vì thời gian trôi mau','Hãy hi sinh vì lý tưởng cao đẹp','Hãy sống giản dị, an nhiên','B',NULL),
(12,2,11,'Tác giả của bài thơ "Đây thôn Vĩ Dạ" là ai?','Xuân Diệu','Huy Cận','Hàn Mặc Tử','Chế Lan Viên','C',NULL),
(12,2,11,'Bài thơ "Tràng giang" của Huy Cận mang âm hưởng của thể thơ nào?','Thơ Đường Trung Quốc','Thơ Pháp hiện đại','Thơ tự do','Thơ Haiku Nhật Bản','A',NULL),
-- ChuDe 13: Van te - Truyen tho Nom
(13,2,11,'"Văn tế nghĩa sĩ Cần Giuộc" của Nguyễn Đình Chiểu ca ngợi ai?','Các vị tướng lĩnh triều đình','Những người nông dân nghĩa sĩ đánh Pháp','Vua Tự Đức','Các nhà thơ yêu nước','B',NULL),
(13,2,11,'"Truyện Kiều" của Nguyễn Du được viết theo thể thơ nào?','Thất ngôn bát cú','Lục bát','Song thất lục bát','Thơ tự do','B',NULL),
(13,2,11,'Đoạn trích "Trao duyên" trong Truyện Kiều nói về việc gì?','Thúy Kiều gặp Kim Trọng','Thúy Kiều nhờ Thúy Vân thay mình kết duyên cùng Kim Trọng','Thúy Kiều từ biệt gia đình','Thúy Kiều gặp Từ Hải','B',NULL),
-- ChuDe 14: Nghi luan
(14,2,11,'Đặc điểm nổi bật của văn nghị luận là:','Kể chuyện theo trình tự thời gian','Dùng lý lẽ và dẫn chứng để thuyết phục','Miêu tả cảnh vật chi tiết','Bộc lộ cảm xúc trực tiếp','B',NULL),
(14,2,11,'"Chiếu cầu hiền" của Ngô Thì Nhậm được viết nhằm mục đích gì?','Ca ngợi vua Quang Trung','Kêu gọi người tài ra giúp nước dưới triều Tây Sơn','Phê phán quan lại tham nhũng','Thể hiện tình yêu quê hương','B',NULL),
(14,2,11,'"Về luân lý xã hội ở nước ta" của Phan Châu Trinh phê phán điều gì?','Sự xâm lược của thực dân Pháp','Sự thiếu đoàn kết, ý thức cộng đồng của người Việt','Chế độ phong kiến lạc hậu','Nạn mê tín dị đoan','B',NULL),
-- ChuDe 15: Kich va nghi luan dau the ky XX
(15,2,11,'Tác phẩm "Vũ Như Tô" của Nguyễn Huy Tưởng thuộc thể loại nào?','Tiểu thuyết','Truyện ngắn','Kịch','Thơ','C',NULL),
(15,2,11,'Nhân vật Vũ Như Tô là hiện thân của mâu thuẫn nào?','Giữa nghĩa vụ và tình yêu','Giữa khát vọng nghệ thuật và trách nhiệm với nhân dân','Giữa giàu và nghèo','Giữa phong kiến và tiến bộ','B',NULL),
(15,2,11,'"Tiếng mẹ đẻ - nguồn giải phóng các dân tộc bị áp bức" của Nguyễn An Ninh thuộc thể loại nào?','Ký sự','Tiểu thuyết','Nghị luận','Truyện ngắn','C',NULL),
-- ===================== TIENG ANH 12 (ChuDe 16-23) =====================
-- ChuDe 16: Unit 1 - Life Stories
(16,3,12,'Choose the correct word: She has been working here _____ 2015.','for','since','from','during','B','Since + mốc thời gian; for + khoảng thời gian'),
(16,3,12,'The word "biography" means:','A story about a fictional character','A written account of someone''s life','A scientific report','A personal diary','B',NULL),
(16,3,12,'Which sentence is grammatically correct?','He have lived in Hanoi for ten years.','He has lived in Hanoi for ten years.','He lived in Hanoi for ten years ago.','He is living in Hanoi since ten years.','B','Present perfect: has/have + V3'),
-- ChuDe 17: Unit 2 - Urbanisation
(17,3,12,'"Urbanisation" means:','The process of building new schools','The process of people moving from rural areas to cities','The growth of agricultural areas','The development of tourism','B',NULL),
(17,3,12,'Choose the correct passive form: "They build many skyscrapers in the city."','Many skyscrapers are built in the city.','Many skyscrapers is built in the city.','Many skyscrapers were built in the city.','Many skyscrapers have built in the city.','A','Chủ ngữ số nhiều + are + V3'),
(17,3,12,'Which is NOT a typical result of urbanisation?','Increased traffic congestion','Growth of slum areas','Decreased pollution','Higher demand for housing','C','Đô thị hóa thường làm tăng ô nhiễm, không phải giảm'),
-- ChuDe 18: Unit 3 - The Green Movement
(18,3,12,'What does "renewable energy" refer to?','Energy from coal and oil','Energy from natural sources that can be replenished, like solar and wind','Energy stored in batteries','Energy from nuclear power','B',NULL),
(18,3,12,'Choose the correct form: If we _____ more trees, the air would be cleaner.','plant','planted','will plant','would plant','B','Second conditional: If + V-ed (quá khứ đơn), would + V'),
(18,3,12,'Which activity helps reduce carbon footprint?','Driving a car every day','Using plastic bags','Taking public transport','Leaving lights on','C',NULL),
-- ChuDe 19: Unit 4 - The Mass Media
(19,3,12,'Which of the following is NOT a form of mass media?','Television','Newspapers','Personal diary','Internet','C','Nhật ký cá nhân không phải phương tiện truyền thông đại chúng'),
(19,3,12,'Choose the correct reported speech: She said, "I am watching TV."','She said she is watching TV.','She said she was watching TV.','She said she watched TV.','She said she has been watching TV.','B','Lùi thì: am/is/are → was/were'),
(19,3,12,'The word "media" in English is:','Singular noun','Plural noun','Uncountable noun','Proper noun','B',NULL),
-- ChuDe 20: Unit 5 - Cultural Identity
(20,3,12,'"Cultural identity" refers to:','The language spoken in a country','The feeling of belonging to a group based on shared culture and traditions','The architecture of a nation','The food of a particular region','B',NULL),
(20,3,12,'Which word is a synonym for "preserve"?','Destroy','Abandon','Maintain','Ignore','C',NULL),
(20,3,12,'Choose the correct relative clause: "The festival _____ is held every year attracts many tourists."','who','whom','which','whose','C','which thay thế cho sự vật/sự việc'),
-- ChuDe 21: Unit 6 - Future Jobs
(21,3,12,'Which job is likely to grow in demand due to technology?','Coal miner','Data scientist','Typewriter operator','Film projectionist','B',NULL),
(21,3,12,'Choose the correct future form: By 2050, robots _____ most factory jobs.','will replace','will have replaced','are replacing','replaced','B','Future perfect: will have + V3 (hoàn thành trước 1 mốc tương lai)'),
(21,3,12,'The word "entrepreneur" describes someone who:','Works for a large corporation','Starts and manages their own business','Works as a government official','Teaches at a university','B',NULL),
-- ChuDe 22: Unit 7 - Economic Reforms
(22,3,12,'Vietnam''s "Doi Moi" reform was launched in:','1975','1986','1990','2000','B',NULL),
(22,3,12,'Which sentence uses the subjunctive correctly?','It is important that he attends the meeting.','It is important that he attend the meeting.','It is important that he will attend the meeting.','It is important that he attended the meeting.','B','Subjunctive: that + S + V (nguyên mẫu)'),
(22,3,12,'"GDP" stands for:','General Domestic Profit','Gross Domestic Product','Global Development Plan','Government Direct Payment','B',NULL),
-- ChuDe 23: Unit 8 - International Integration
(23,3,12,'ASEAN stands for:','Asian Social and Economic Association of Nations','Association of Southeast Asian Nations','Asian Strategic Economic and Administrative Network','Association of South and East Asian Nations','B',NULL),
(23,3,12,'"Globalisation" means:','The spread of diseases worldwide','The process of interaction and integration among people and countries','The growth of national economies only','The spread of one culture over others','B',NULL),
(23,3,12,'Choose the correct preposition: Vietnam became a member _____ the WTO in 2007.','for','of','with','in','B','member of = thành viên của'),
-- ===================== VAT LY 10 (ChuDe 24-30) =====================
-- ChuDe 24: Dong hoc chat diem
(24,4,10,'Vật chuyển động thẳng đều v = 10 m/s. Quãng đường đi được trong 5 giây là:','2 m','50 m','15 m','100 m','B','s = v×t = 10×5 = 50 m'),
(24,4,10,'Đơn vị của gia tốc trong hệ SI là:','m/s','km/h','m/s²','cm/s²','C',NULL),
(24,4,10,'Vật chuyển động nhanh dần đều khi:','Vận tốc và gia tốc cùng chiều','Vận tốc và gia tốc ngược chiều','Gia tốc bằng 0','Vận tốc không đổi','A',NULL),
-- ChuDe 25: Dong luc hoc
(25,4,10,'Theo định luật II Newton, lực tác dụng vào vật bằng:','F = mv','F = ma','F = m/a','F = m + a','B',NULL),
(25,4,10,'Khối lượng 5 kg, gia tốc 3 m/s². Lực tác dụng lên vật là:','8 N','15 N','1.67 N','2 N','B','F = ma = 5×3 = 15 N'),
(25,4,10,'Định luật III Newton phát biểu:','Lực và phản lực cùng chiều','Lực và phản lực bằng nhau về độ lớn, ngược chiều nhau','Lực và phản lực tác dụng lên cùng một vật','Lực tác dụng luôn lớn hơn phản lực','B',NULL),
-- ChuDe 26: Can bang vat ran
(26,4,10,'Điều kiện cân bằng của vật rắn không có trục quay là:','Hợp lực tác dụng lên vật bằng 0','Chỉ có một lực tác dụng','Vật đứng yên','Không có ma sát','A',NULL),
(26,4,10,'Quy tắc mô men lực được áp dụng cho:','Vật rắn không có trục quay cố định','Vật rắn có trục quay cố định','Chất lỏng','Hạt cơ bản','B',NULL),
(26,4,10,'Trọng tâm của vật đồng chất hình tròn nằm tại:','Mép của hình tròn','Tâm của hình tròn','Bất kỳ điểm nào trên đường kính','Phía dưới tâm','B',NULL),
-- ChuDe 27: Cac dinh luat bao toan
(27,4,10,'Động lượng của vật được tính bằng:','p = mv','p = mv²','p = ½mv²','p = m/v','A',NULL),
(27,4,10,'Định luật bảo toàn năng lượng phát biểu:','Năng lượng có thể bị mất đi','Năng lượng không tự nhiên sinh ra và không tự nhiên mất đi','Năng lượng luôn tăng theo thời gian','Năng lượng chỉ tồn tại ở dạng nhiệt','B',NULL),
(27,4,10,'Công thức tính động năng của vật là:','Wđ = mv','Wđ = mgh','Wđ = ½mv²','Wđ = mv²','C',NULL),
-- ChuDe 28: Chat khi
(28,4,10,'Định luật Boyle - Mariotte áp dụng cho quá trình:','Đẳng nhiệt','Đẳng áp','Đẳng tích','Đoạn nhiệt','A','pV = const khi T = const'),
(28,4,10,'Khí lý tưởng ở nhiệt độ không đổi. Khi áp suất tăng gấp đôi thì thể tích:','Tăng gấp đôi','Giảm còn một nửa','Không thay đổi','Tăng bốn lần','B','pV = const => p tăng 2 lần, V giảm 2 lần'),
(28,4,10,'Đơn vị của áp suất trong hệ SI là:','N/m','Pa (Pascal)','N (Newton)','J (Joule)','B',NULL),
-- ChuDe 29: Nhiet dong luc hoc
(29,4,10,'Nguyên lý I nhiệt động lực học nói về:','Chiều tự nhiên của các quá trình nhiệt','Sự bảo toàn năng lượng trong các quá trình nhiệt','Hiệu suất tối đa của động cơ nhiệt','Nhiệt độ tuyệt đối bằng 0','B',NULL),
(29,4,10,'Hiệu suất động cơ nhiệt lý tưởng phụ thuộc vào:','Loại chất khí sử dụng','Nhiệt độ của nguồn nóng và nguồn lạnh','Tốc độ của piston','Thể tích xy-lanh','B',NULL),
(29,4,10,'Quá trình nào không thể xảy ra tự nhiên theo nguyên lý II nhiệt động lực học?','Nhiệt truyền từ vật nóng sang vật lạnh','Nhiệt tự truyền từ vật lạnh sang vật nóng','Khí giãn nở ra không gian rỗng','Đồng bị oxi hóa','B',NULL),
-- ChuDe 30: Chat long - Chat ran - Su chuyen the
(30,4,10,'Sự bay hơi xảy ra ở:','Chỉ ở nhiệt độ sôi','Mọi nhiệt độ trên bề mặt chất lỏng','Chỉ khi đun nóng','Chỉ ở nhiệt độ rất cao','B',NULL),
(30,4,10,'Nhiệt nóng chảy riêng là nhiệt lượng cần để:','Đun sôi 1 kg chất đó','Làm nóng chảy hoàn toàn 1 kg chất ở nhiệt độ nóng chảy','Hóa hơi 1 kg chất đó','Tăng nhiệt độ 1 kg chất thêm 1°C','B',NULL),
(30,4,10,'Hiện tượng mao dẫn là do:','Lực hấp dẫn của Trái Đất','Lực căng bề mặt của chất lỏng','Áp suất khí quyển','Nhiệt độ cao','B',NULL),
-- ===================== HOA HOC 11 (ChuDe 31-38) =====================
-- ChuDe 31: Su dien li
(31,5,11,'Chất nào sau đây là chất điện li mạnh?','CH3COOH','HCl','H2O','C6H12O6','B','HCl phân li hoàn toàn trong nước'),
(31,5,11,'Phương trình điện li của NaCl là:','NaCl → Na + Cl','NaCl → Na⁺ + Cl⁻','NaCl → Na²⁺ + Cl²⁻','NaCl → 2Na⁺ + Cl₂','B',NULL),
(31,5,11,'pH của dung dịch trung tính ở 25°C là:','0','7','14','1','B','[H⁺] = [OH⁻] = 10⁻⁷ M => pH = 7'),
-- ChuDe 32: Nito va hop chat
(32,5,11,'Nitơ chiếm khoảng bao nhiêu % thể tích không khí?','21%','78%','1%','50%','B',NULL),
(32,5,11,'NH3 tác dụng với HCl tạo ra:','N2 + H2O','NH4Cl','NaCl','NO2','B','NH3 + HCl → NH4Cl'),
(32,5,11,'HNO3 đặc tác dụng với Cu tạo ra khí:','NO','NO2','N2','NH3','B','HNO3 đặc + Cu → Cu(NO3)2 + NO2↑ + H2O'),
-- ChuDe 33: Photpho va hop chat
(33,5,11,'Dạng thù hình nào của Photpho hoạt động hơn về hóa học?','Photpho đỏ','Photpho trắng','Photpho đen','Photpho vàng','B','Photpho trắng có cấu trúc P4, hoạt động mạnh hơn'),
(33,5,11,'Photpho đỏ được dùng trong sản xuất:','Đạn pháo','Diêm an toàn','Phân bón đạm','Thuốc nổ TNT','B',NULL),
(33,5,11,'H3PO4 là axit:','Mạnh, 1 nấc','Trung bình, 3 nấc','Yếu, 1 nấc','Mạnh, 3 nấc','B',NULL),
-- ChuDe 34: Cacbon - Silic
(34,5,11,'Dạng thù hình nào của Cacbon cứng nhất?','Than chì (graphit)','Kim cương','Than hoạt tính','Fuleren','B',NULL),
(34,5,11,'CO2 tác dụng với dung dịch NaOH dư tạo sản phẩm chính là:','NaHCO3','Na2CO3','Na2O','NaOH dư','B','CO2 + 2NaOH(dư) → Na2CO3 + H2O'),
(34,5,11,'Silic là nguyên liệu quan trọng trong sản xuất:','Thủy tinh và chất bán dẫn','Phân bón','Xăng dầu','Nhựa tổng hợp','A',NULL),
-- ChuDe 35: Dai cuong hoa huu co
(35,5,11,'Hợp chất hữu cơ nhất thiết phải có nguyên tố nào?','Nitơ','Cacbon','Oxi','Hiđro','B',NULL),
(35,5,11,'Phản ứng đặc trưng của hợp chất hữu cơ no là:','Phản ứng cộng','Phản ứng thế','Phản ứng oxi hóa - khử','Phản ứng trùng hợp','B',NULL),
(35,5,11,'Đồng đẳng là những chất có:','Cùng công thức phân tử','Cùng công thức chung, hơn kém nhau một hay nhiều nhóm CH2','Cùng cấu tạo hóa học','Cùng tính chất vật lý','B',NULL),
-- ChuDe 36: Hidrocacbon no (Ankan)
(36,5,11,'Công thức chung của ankan (mạch hở) là:','CnH2n','CnH2n+2','CnH2n-2','CnHn','B',NULL),
(36,5,11,'Metan (CH4) là thành phần chủ yếu của:','Khí propan','Khí tự nhiên (khí thiên nhiên)','Khí than','Khí CO2','B',NULL),
(36,5,11,'Phản ứng đặc trưng của ankan là:','Phản ứng cộng','Phản ứng thế với halogen','Phản ứng trùng hợp','Phản ứng oxi hóa không hoàn toàn','B',NULL),
-- ChuDe 37: Hidrocacbon khong no
(37,5,11,'Etilen (C2H4) có bao nhiêu liên kết π trong phân tử?','0','1','2','3','B','CH2=CH2 có 1 liên kết đôi gồm 1σ + 1π'),
(37,5,11,'Phản ứng đặc trưng của anken là:','Phản ứng thế','Phản ứng cộng','Phản ứng tách','Phản ứng đốt cháy','B',NULL),
(37,5,11,'Axetilen (C2H2) thuộc dãy đồng đẳng nào?','Ankan','Anken','Ankin','Aren','C','C2H2 có liên kết ba, thuộc dãy ankin'),
-- ChuDe 38: Ancol - Phenol - Andehit
(38,5,11,'Etanol (C2H5OH) tác dụng với Na giải phóng khí gì?','O2','H2','CO2','CH4','B','2C2H5OH + 2Na → 2C2H5ONa + H2↑'),
(38,5,11,'Phản ứng tráng bạc dùng để nhận biết nhóm chức nào?','Nhóm OH','Nhóm CHO (anđehit)','Nhóm COOH','Nhóm NH2','B',NULL),
(38,5,11,'Phenol (C6H5OH) khác ancol ở chỗ nhóm OH gắn vào:','Mạch thẳng','Vòng benzen','Nguyên tử N','Nguyên tử S','B',NULL),
-- ===================== SINH HOC 9 (ChuDe 39-46) =====================
-- ChuDe 39: Di truyen - Menden
(39,6,9,'Tỉ lệ phân li kiểu hình ở F2 trong phép lai một tính trạng (Aa × Aa) là:','1 : 1','3 : 1','1 : 2 : 1','9 : 3 : 3 : 1','B','AA : Aa : aa = 1:2:1, kiểu hình trội : lặn = 3:1'),
(39,6,9,'Alen trội là alen:','Chỉ biểu hiện ở trạng thái đồng hợp','Biểu hiện ngay cả khi ở trạng thái dị hợp','Luôn có lợi cho sinh vật','Xuất hiện sau alen lặn','B',NULL),
(39,6,9,'Lai phân tích là phép lai giữa cá thể cần xác định kiểu gen với:','Cá thể đồng hợp trội (AA)','Cá thể đồng hợp lặn (aa)','Cá thể dị hợp (Aa)','Bất kỳ cá thể nào','B','Lai phân tích giúp xác định cá thể trội là AA hay Aa'),
-- ChuDe 40: Nhiem sac the
(40,6,9,'Số lượng NST trong tế bào sinh dưỡng của người là:','23','46','44','48','B','2n = 46 NST ở người'),
(40,6,9,'Nguyên phân xảy ra ở loại tế bào nào?','Tế bào sinh dục','Tế bào sinh dưỡng','Tế bào thần kinh không phân chia','Hồng cầu','B',NULL),
(40,6,9,'Kết quả của giảm phân là tạo ra:','2 tế bào con có 2n NST','4 tế bào con có n NST','4 tế bào con có 2n NST','2 tế bào con có n NST','B','Giảm phân I và II tạo 4 tế bào con đơn bội (n)'),
-- ChuDe 41: ADN - Gen - Protein
(41,6,9,'ADN được cấu tạo từ các đơn phân gọi là:','Axit amin','Nuclêôtit','Glucôzơ','Axit béo','B',NULL),
(41,6,9,'Nguyên tắc bổ sung trong ADN quy định:','A liên kết với G, T liên kết với X','A liên kết với T, G liên kết với X','A liên kết với X, G liên kết với T','A liên kết với A, G liên kết với G','B','A=T, G≡X theo nguyên tắc bổ sung Watson-Crick'),
(41,6,9,'Quá trình tổng hợp ARN từ ADN được gọi là:','Dịch mã','Phiên mã','Nhân đôi ADN','Đột biến','B',NULL),
-- ChuDe 42: Bien di
(42,6,9,'Đột biến gen là:','Sự thay đổi về số lượng NST','Sự thay đổi trong cấu trúc của gen','Sự thay đổi kiểu hình do môi trường','Sự trao đổi chéo giữa các NST','B',NULL),
(42,6,9,'Thường biến là:','Biến dị di truyền được','Biến dị không di truyền, do môi trường gây ra','Đột biến NST','Đột biến gen','B',NULL),
(42,6,9,'Tác nhân vật lý nào có thể gây đột biến gen?','Nhiệt độ thấp','Tia X, tia UV','Ánh sáng mặt trời bình thường','Nước','B',NULL),
-- ChuDe 43: Di truyen hoc nguoi
(43,6,9,'Bệnh Down ở người là do:','Mất một NST 21','Thừa một NST 21 (3 NST 21)','Đột biến gen trên NST 21','Thiếu NST giới tính','B','Thể ba (2n+1) ở cặp NST số 21'),
(43,6,9,'Bệnh mù màu đỏ-lục ở người do gen lặn nằm trên:','NST thường','NST X','NST Y','Ti thể','B','Gen lặn liên kết X, nam giới (XY) dễ bị bệnh hơn'),
(43,6,9,'Phương pháp nào được dùng để nghiên cứu di truyền người?','Lai phân tích','Nghiên cứu phả hệ','Gây đột biến nhân tạo','Lai thuận nghịch','B',NULL),
-- ChuDe 44: Ung dung di truyen hoc
(44,6,9,'Phương pháp nào tạo ra giống thuần chủng nhanh nhất?','Lai xa','Gây đột biến nhân tạo','Tự thụ phấn nhiều thế hệ','Nuôi cấy hạt phấn rồi lưỡng bội hóa bằng colchicine','D','Tạo dòng đơn bội rồi lưỡng bội hóa cho thuần chủng ngay F1'),
(44,6,9,'Colchicine được dùng để:','Gây đột biến gen','Cản trở thoi phân bào, tạo thể đa bội','Tiêu diệt vi khuẩn','Tăng tốc độ phân bào','B',NULL),
(44,6,9,'ADN tái tổ hợp là:','ADN tự nhân đôi trong tế bào','ADN được tạo ra bằng cách ghép đoạn ADN của các loài khác nhau','ADN bị đột biến','ADN của vi rút','B',NULL),
-- ChuDe 45: Moi truong va nhan to sinh thai
(45,6,9,'Nhân tố sinh thái là:','Chỉ là các yếu tố vô sinh của môi trường','Tất cả các yếu tố của môi trường ảnh hưởng đến sinh vật','Chỉ là các sinh vật sống cùng nhau','Chỉ là yếu tố khí hậu','B',NULL),
(45,6,9,'Giới hạn sinh thái là:','Số lượng cá thể tối đa trong quần thể','Khoảng giá trị của một nhân tố sinh thái mà sinh vật có thể tồn tại','Phạm vi địa lý của một loài','Mức tiêu thụ thức ăn tối đa','B',NULL),
(45,6,9,'Cây ưa bóng khác cây ưa sáng ở chỗ:','Cần nhiều nước hơn','Quang hợp tốt trong điều kiện ánh sáng yếu','Lá to hơn','Sống ở vùng nhiệt đới','B',NULL),
-- ChuDe 46: He sinh thai
(46,6,9,'Sinh vật sản xuất trong hệ sinh thái là:','Động vật ăn thực vật','Thực vật và vi khuẩn quang hợp','Động vật ăn thịt','Vi khuẩn phân hủy','B',NULL),
(46,6,9,'Chuỗi thức ăn thường bắt đầu bằng:','Động vật ăn thịt','Sinh vật sản xuất (cây xanh)','Sinh vật phân hủy','Động vật ăn cỏ','B',NULL),
(46,6,9,'Hiện tượng nào thể hiện sự cân bằng sinh học?','Số lượng thỏ tăng vô hạn trong rừng','Khi thỏ tăng, sói tăng rồi khống chế quần thể thỏ','Tất cả sinh vật trong rừng đều chết','Cây rừng bị chặt phá hoàn toàn','B',NULL),
-- ===================== LICH SU 8 (ChuDe 47-54) =====================
-- ChuDe 47: Chau Au - My cuoi XVIII - dau XX
(47,7,8,'Cách mạng tư sản Pháp (1789) nêu cao khẩu hiệu nào?','Độc lập - Tự do - Hạnh phúc','Tự do - Bình đẳng - Bác ái','Dân chủ - Cộng hòa - Tiến bộ','Hòa bình - Bình đẳng - Dân chủ','B',NULL),
(47,7,8,'Cuộc Cách mạng công nghiệp đầu tiên nổ ra ở nước nào?','Pháp','Anh','Đức','Mỹ','B','Anh là nước đầu tiên tiến hành cách mạng công nghiệp (thế kỷ XVIII)'),
(47,7,8,'Công xã Pari (1871) là nhà nước:','Tư bản chủ nghĩa đầu tiên','Vô sản đầu tiên trong lịch sử','Phong kiến cuối cùng','Thuộc địa của Anh','B',NULL),
-- ChuDe 48: A - Phi - My Latinh cuoi XIX
(48,7,8,'Phong trào Nghĩa Hòa Đoàn (1900) nổ ra ở:','Nhật Bản','Trung Quốc','Ấn Độ','Triều Tiên','B',NULL),
(48,7,8,'Cuộc Duy tân Minh Trị (1868) đã đưa Nhật Bản trở thành:','Thuộc địa của Anh','Nước tư bản chủ nghĩa phát triển ở châu Á','Nước xã hội chủ nghĩa','Nước phong kiến mạnh nhất châu Á','B',NULL),
(48,7,8,'Cuộc khởi nghĩa Xipay (1857-1859) nổ ra ở:','Trung Quốc','Ấn Độ','Miến Điện','Philippin','B',NULL),
-- ChuDe 49: Chien tranh the gioi thu nhat
(49,7,8,'Chiến tranh thế giới thứ nhất bùng nổ vào năm nào?','1912','1914','1916','1918','B',NULL),
(49,7,8,'Sự kiện trực tiếp dẫn đến Chiến tranh thế giới thứ nhất là:','Anh tuyên chiến với Đức','Thái tử Áo-Hung bị ám sát tại Sarajevo','Đức xâm lược Pháp','Nga tổng động viên quân đội','B',NULL),
(49,7,8,'Chiến tranh thế giới thứ nhất kết thúc năm 1918 với sự thất bại của:','Khối Hiệp ước (Anh, Pháp, Nga)','Khối Liên minh (Đức, Áo-Hung, I-ta-li-a)','Cả hai phe','Chỉ riêng Đức','B',NULL),
-- ChuDe 50: Cach mang Nga 1917
(50,7,8,'Cách mạng tháng Mười Nga 1917 do ai lãnh đạo?','Stalin','Lênin (đứng đầu Đảng Bônsêvích)','Trosky','Sa hoàng Nikolai II','B',NULL),
(50,7,8,'Chính sách kinh tế mới (NEP) ở Liên Xô (1921) được thực hiện nhằm:','Công nghiệp hóa nhanh chóng','Khôi phục kinh tế sau chiến tranh và nội chiến','Chuẩn bị cho Chiến tranh thế giới thứ hai','Tập thể hóa nông nghiệp','B',NULL),
(50,7,8,'Liên bang Cộng hòa xã hội chủ nghĩa Xô viết (Liên Xô) được thành lập năm:','1917','1922','1924','1928','B',NULL),
-- ChuDe 51: Tu ban giua hai cuoc chien
(51,7,8,'Cuộc khủng hoảng kinh tế thế giới 1929-1933 bùng phát đầu tiên ở:','Đức','Mỹ','Anh','Pháp','B',NULL),
(51,7,8,'Chủ nghĩa phát xít lên nắm quyền ở Đức vào năm:','1929','1933','1936','1939','B','Hitler lên nắm quyền ngày 30/1/1933'),
(51,7,8,'Chính sách "Mới" (New Deal) của Tổng thống Roosevelt nhằm:','Mở rộng chiến tranh','Đưa nước Mỹ thoát khỏi khủng hoảng kinh tế','Thiết lập chế độ độc tài','Tham chiến ở châu Âu','B',NULL),
-- ChuDe 52: Chien tranh the gioi thu hai
(52,7,8,'Chiến tranh thế giới thứ hai kết thúc vào năm nào?','1943','1945','1947','1950','B',NULL),
(52,7,8,'Trận đánh nào là bước ngoặt của mặt trận Xô - Đức?','Trận Mátxcơva (1941)','Trận Xtalingrat (1942-1943)','Trận Kuốc-xcơ (1943)','Trận Béc-lin (1945)','B',NULL),
(52,7,8,'Nhật Bản đầu hàng Đồng minh vô điều kiện (8/1945) do:','Mỹ ném bom nguyên tử và Liên Xô tuyên chiến với Nhật','Quân Đức bại trận ở châu Âu','Nhật hết lương thực và vũ khí','Dân Nhật nổi dậy khởi nghĩa','A',NULL),
-- ChuDe 53: VN 1858 - cuoi XIX
(53,7,8,'Thực dân Pháp nổ súng tấn công Việt Nam lần đầu vào năm nào và ở đâu?','1858 - Đà Nẵng','1859 - Gia Định','1862 - Hà Nội','1867 - Hà Tiên','A',NULL),
(53,7,8,'Hiệp ước nào buộc triều Nguyễn nhường 3 tỉnh miền Đông Nam Kỳ cho Pháp (1862)?','Hiệp ước Hác-măng','Hiệp ước Nhâm Tuất','Hiệp ước Giáp Tuất','Hiệp ước Pa-tơ-nốt','B',NULL),
(53,7,8,'Cuộc khởi nghĩa nào tiêu biểu nhất trong phong trào Cần Vương?','Khởi nghĩa Ba Đình','Khởi nghĩa Hương Khê','Khởi nghĩa Bãi Sậy','Khởi nghĩa Yên Thế','B','Hương Khê (1885-1896) là lớn nhất, kéo dài nhất trong phong trào Cần Vương'),
-- ChuDe 54: VN dau XX - 1918
(54,7,8,'Phong trào Đông Du (1905-1909) do ai khởi xướng?','Phan Châu Trinh','Phan Bội Châu','Nguyễn Ái Quốc','Lương Văn Can','B',NULL),
(54,7,8,'Đông Kinh nghĩa thục (1907) được thành lập nhằm mục đích chính là:','Huấn luyện quân sự chống Pháp','Mở trường học, nâng cao dân trí, truyền bá tư tưởng mới','Tổ chức bạo động vũ trang','Buôn bán, gây quỹ chống Pháp','B',NULL),
(54,7,8,'Vụ đầu độc lính Pháp tại Hà Nội năm 1908 (Hà Thành đầu độc) là hành động của:','Nghĩa quân Yên Thế','Binh lính người Việt trong quân đội Pháp nổi dậy','Học sinh Đông Kinh nghĩa thục','Hội Duy tân','B',NULL);

-- =========================================================
-- Cau_hoi_trac_nghiem_dung_sai: 2 cau / chu de = 108 cau
-- DapAn: "Đ,S,Đ,Đ" tuong ung MenhDe1..4 (Đ=Đúng, S=Sai)
-- =========================================================
INSERT INTO Cau_hoi_trac_nghiem_dung_sai
(ID_ChuDe, ID_MonHoc, ID_KhoiLop,
 NoiDungCauHoi_TracNghiemDungSai,
 NoiDungMenhDe1_TracNghiemDungSai,
 NoiDungMenhDe2_TracNghiemDungSai,
 NoiDungMenhDe3_TracNghiemDungSai,
 NoiDungMenhDe4_TracNghiemDungSai,
 DapAn_TracNghiem4PhuongAn,
 HuongDanGiaiMenhDe1_TracNghiemDungSai,
 HuongDanGiaiMenhDe2_TracNghiemDungSai,
 HuongDanGiaiMenhDe3_TracNghiemDungSai,
 HuongDanGiaiMenhDe4_TracNghiemDungSai)
VALUES
-- ===================== TOAN 10 (ChuDe 1-8) =====================
-- ChuDe 1: Menh de va tap hop
(1,1,10,'Xét các mệnh đề về tập hợp sau đây:',
 'Tập rỗng ∅ không có phần tử nào',
 'Tập hợp {1,2} ∩ {3,4} = {1,2,3,4}',
 'Nếu A ⊂ B thì A ∪ B = B',
 'Phủ định của "∀x∈ℕ: x>0" là "∃x∈ℕ: x≤0"',
 'Đ,S,Đ,Đ',
 'Đúng: định nghĩa tập rỗng','Sai: giao của hai tập rời nhau là ∅','Đúng: nếu A là tập con của B thì hợp bằng B','Đúng: phủ định của "mọi x" là "tồn tại x không"'),
(1,1,10,'Cho A={1,2,3}, B={2,3,4}. Xét các khẳng định:',
 'A ∪ B = {1,2,3,4}',
 'A ∩ B = {1,2,3,4}',
 'A \\ B = {1}',
 'B \\ A = {4}',
 'Đ,S,Đ,Đ',
 'Đúng: hợp gồm tất cả phần tử của hai tập','Sai: giao chỉ là {2,3}','Đúng: phần tử trong A mà không thuộc B','Đúng: phần tử trong B mà không thuộc A'),
-- ChuDe 2: Ham so
(2,1,10,'Xét hàm số y = x² - 4x + 3:',
 'Đây là hàm số bậc hai với a = 1 > 0',
 'Parabol quay xuống (bề lõm quay xuống)',
 'Đỉnh parabol tại điểm (2, -1)',
 'Hàm số đạt giá trị nhỏ nhất bằng -1',
 'Đ,S,Đ,Đ',
 'Đúng: a=1>0 là hàm bậc hai','Sai: a>0 nên parabol quay lên','Đúng: x=-b/2a=2; y=4-8+3=-1','Đúng: giá trị nhỏ nhất tại đỉnh bằng -1'),
(2,1,10,'Về hàm số y = 2x + 3:',
 'Hàm số đồng biến trên toàn tập số thực',
 'Đồ thị song song với trục hoành',
 'Đồ thị cắt trục Oy tại điểm (0; 3)',
 'Đây là hàm số bậc nhất',
 'Đ,S,Đ,Đ',
 'Đúng: hệ số góc a=2>0','Sai: đồ thị là đường thẳng nghiêng','Đúng: x=0 thì y=3','Đúng: dạng y=ax+b'),
-- ChuDe 3: Phuong trinh va bat phuong trinh
(3,1,10,'Về phương trình x² - 4 = 0:',
 'Phương trình có 2 nghiệm thực',
 'Hai nghiệm là x=2 và x=4',
 'Tổng hai nghiệm bằng 0',
 'Tích hai nghiệm bằng -4',
 'Đ,S,Đ,Đ',
 'Đúng: Δ=16>0 có 2 nghiệm','Sai: nghiệm là x=±2','Đúng: 2+(-2)=0','Đúng: 2×(-2)=-4'),
(3,1,10,'Về bất phương trình 3x + 6 > 0:',
 'Tập nghiệm là x > -2',
 'x = -3 là một nghiệm',
 'x = 0 thỏa mãn bất phương trình',
 'Tập nghiệm là (-2; +∞)',
 'Đ,S,Đ,Đ',
 'Đúng: 3x>-6 → x>-2','Sai: 3(-3)+6=-3<0 không thỏa','Đúng: 3(0)+6=6>0','Đúng: tập nghiệm mở tại -2'),
-- ChuDe 4: He phuong trinh
(4,1,10,'Cho hệ phương trình {x+y=4; x-y=2}:',
 'Nghiệm của hệ là (x;y) = (3;1)',
 'Hệ phương trình vô nghiệm',
 'Hệ có nghiệm duy nhất',
 'Tại nghiệm, x > y',
 'Đ,S,Đ,Đ',
 'Đúng: cộng 2pt: 2x=6→x=3; y=1','Sai: hệ có nghiệm duy nhất','Đúng: hai đường thẳng cắt nhau','Đúng: 3>1'),
(4,1,10,'Về hệ phương trình bậc nhất hai ẩn:',
 'Hệ có thể có vô số nghiệm nếu hai phương trình trùng nhau',
 'Hệ luôn có nghiệm duy nhất',
 'Hệ có thể vô nghiệm nếu hai đường thẳng song song',
 'Nếu hai đường thẳng cắt nhau thì hệ có nghiệm duy nhất',
 'Đ,S,Đ,Đ',
 'Đúng: trùng nhau thì vô số nghiệm','Sai: có thể vô nghiệm hoặc vô số nghiệm','Đúng: song song thì không giao nhau','Đúng: giao nhau cho đúng 1 điểm'),
-- ChuDe 5: Vecto
(5,1,10,'Cho vectơ a⃗ = (3;4). Các khẳng định:',
 '|a⃗| = 5',
 '|a⃗| = 7',
 'Vectơ đơn vị cùng hướng a⃗ là (3/5; 4/5)',
 'a⃗ ≠ 0⃗',
 'Đ,S,Đ,Đ',
 'Đúng: √(9+16)=5','Sai: không phải 3+4','Đúng: chia độ dài vectơ cho modul','Đúng: vì |a⃗|=5≠0'),
(5,1,10,'Cho A(1;2) và B(4;6). Các khẳng định:',
 'Vectơ AB⃗ = (3;4)',
 '|AB⃗| = 7',
 'Trung điểm của AB là M(2,5; 4)',
 'AB⃗ = -BA⃗',
 'Đ,S,Đ,Đ',
 'Đúng: (4-1; 6-2)=(3;4)','Sai: |AB|=√(9+16)=5','Đúng: ((1+4)/2; (2+6)/2)=(2,5;4)','Đúng: hai vectơ đối nhau'),
-- ChuDe 6: Tich vo huong
(6,1,10,'Cho a⃗=(1;0) và b⃗=(0;1). Các khẳng định:',
 'a⃗·b⃗ = 0',
 'Hai vectơ cùng phương',
 'Góc giữa hai vectơ là 90°',
 '|a⃗| = |b⃗| = 1',
 'Đ,S,Đ,Đ',
 'Đúng: 1×0+0×1=0','Sai: hai vectơ vuông góc','Đúng: tích vô hướng=0 ⟺ góc 90°','Đúng: đây là hai vectơ đơn vị'),
(6,1,10,'Cho a⃗=(2;1) và b⃗=(1;-2). Các khẳng định:',
 'a⃗·b⃗ = 0',
 'Hai vectơ a⃗ và b⃗ cùng phương',
 'Hai vectơ a⃗ và b⃗ vuông góc với nhau',
 '|a⃗| = √5',
 'Đ,S,Đ,Đ',
 'Đúng: 2×1+1×(-2)=0','Sai: nếu cùng phương thì a⃗·b⃗ ≠ 0','Đúng: tích vô hướng=0','Đúng: √(4+1)=√5'),
-- ChuDe 7: Phuong phap toa do
(7,1,10,'Đường thẳng d: y = 3x + 1 có các tính chất:',
 'Hệ số góc k = 3',
 'Đường thẳng đi qua gốc tọa độ O(0;0)',
 'Đường thẳng cắt trục Oy tại (0;1)',
 'Song song với đường thẳng y = 3x - 5',
 'Đ,S,Đ,Đ',
 'Đúng: hệ số của x','Sai: khi x=0 thì y=1≠0','Đúng: thế x=0','Đúng: cùng hệ số góc, khác tung độ gốc'),
(7,1,10,'Khoảng cách từ O(0;0) đến đường thẳng 3x+4y-10=0:',
 'd(O,đt) = 2',
 'd(O,đt) = 10',
 'Đường thẳng cắt Ox tại điểm (10/3; 0)',
 'Đường thẳng cắt Oy tại điểm (0; 5/2)',
 'Đ,S,Đ,Đ',
 'Đúng: d=|3(0)+4(0)-10|/√(9+16)=10/5=2','Sai: chưa chia cho √(a²+b²)','Đúng: thế y=0: x=10/3','Đúng: thế x=0: y=10/4=5/2'),
-- ChuDe 8: Thong ke va xac suat
(8,1,10,'Tung một đồng xu cân đối. Các khẳng định:',
 'Xác suất ra mặt ngửa là 1/2',
 'Xác suất ra mặt sấp là 2/3',
 'Tổng xác suất tất cả kết quả có thể bằng 1',
 '"Ra mặt ngửa" và "ra mặt sấp" là hai biến cố đối nhau',
 'Đ,S,Đ,Đ',
 'Đúng: xác suất đồng đều','Sai: phải là 1/2','Đúng: tiên đề xác suất','Đúng: hai biến cố xung khắc và đầy đủ'),
(8,1,10,'Dãy số: 3, 5, 7, 9, 11. Các khẳng định:',
 'Số trung bình cộng của dãy bằng 7',
 'Số trung bình cộng của dãy bằng 8',
 'Số trung vị (median) của dãy là 7',
 'Dãy số là cấp số cộng với công sai d = 2',
 'Đ,S,Đ,Đ',
 'Đúng: (3+5+7+9+11)/5=35/5=7','Sai: trung bình là 7 không phải 8','Đúng: số ở giữa khi đã sắp xếp','Đúng: mỗi số hơn số trước 2 đơn vị'),
-- ===================== NGU VAN 11 (ChuDe 9-15) =====================
-- ChuDe 9: Tho chu Nom va chu Han
(9,2,11,'Về bài thơ "Tự tình II" của Hồ Xuân Hương:',
 'Bài thơ được viết theo thể thất ngôn bát cú Đường luật',
 'Tác giả bộc lộ tâm trạng vui vẻ, mãn nguyện',
 'Tác giả là người phụ nữ có cuộc đời nhiều bất hạnh',
 'Bài thơ thể hiện khát vọng hạnh phúc của người phụ nữ',
 'Đ,S,Đ,Đ',
 'Đúng: 8 câu, mỗi câu 7 chữ','Sai: tâm trạng cô đơn, buồn tủi','Đúng: hai lần lấy chồng đều dang dở','Đúng: khát vọng thoát khỏi cảnh hẩm hiu'),
(9,2,11,'Về bài "Câu cá mùa thu" của Nguyễn Khuyến:',
 'Bài thơ được viết bằng chữ Nôm',
 'Khung cảnh được miêu tả là mùa xuân',
 'Mặt ao được miêu tả với hình ảnh nước "trong veo"',
 'Bài thơ thể hiện tình yêu thiên nhiên và nỗi lo âu về thời cuộc',
 'Đ,S,Đ,Đ',
 'Đúng: bài thơ viết bằng chữ Nôm','Sai: bài thơ miêu tả mùa thu','Đúng: "Ao thu lạnh lẽo nước trong veo"','Đúng: cảnh thu đẹp nhưng ẩn chứa nỗi buồn thời thế'),
-- ChuDe 10: Van xuoi lang man
(10,2,11,'Về truyện ngắn "Chữ người tử tù" của Nguyễn Tuân:',
 'Huấn Cao là nhân vật trung tâm của tác phẩm',
 'Huấn Cao cho chữ trong phòng khách sang trọng',
 'Cảnh cho chữ diễn ra trong đêm tối, trong buồng tối nhà tù',
 'Tác phẩm thể hiện quan niệm cái đẹp luôn gắn với cái thiện',
 'Đ,S,Đ,Đ',
 'Đúng: Huấn Cao là người tài hoa, có khí phách','Sai: cho chữ trong buồng tối chật hẹp của nhà tù','Đúng: "cảnh tượng xưa nay chưa từng có"','Đúng: đây là tư tưởng thẩm mỹ của Nguyễn Tuân'),
(10,2,11,'Về truyện ngắn "Hai đứa trẻ" của Thạch Lam:',
 'Hai nhân vật chính là chị em Liên và An',
 'Hai chị em thức khuya để đi chơi',
 'Chuyến tàu đêm là biểu tượng của ánh sáng và hi vọng',
 'Bối cảnh là một phố huyện nghèo nàn, tĩnh lặng',
 'Đ,S,Đ,Đ',
 'Đúng: hai chị em bán hàng tạp hoá','Sai: thức chờ chuyến tàu từ Hà Nội','Đúng: tàu mang ánh sáng và ký ức tuổi thơ','Đúng: khung cảnh buồn tẻ, tù túng'),
-- ChuDe 11: Van xuoi hien thuc phe phan
(11,2,11,'Về nhân vật Chí Phèo trong tác phẩm của Nam Cao:',
 'Chí Phèo từng là người nông dân lương thiện trước khi vào tù',
 'Chí Phèo bị tha hóa vì bản thân lười biếng, sa đọa',
 'Thị Nở là người đánh thức bản chất lương thiện trong Chí',
 'Chí Phèo đâm chết Bá Kiến rồi tự vẫn ở cuối truyện',
 'Đ,S,Đ,Đ',
 'Đúng: hắn vốn hiền lành, cày thuê cuốc mướn','Sai: xã hội thực dân phong kiến đẩy hắn vào đường cùng','Đúng: bát cháo hành của Thị Nở làm hắn tỉnh ngộ','Đúng: kết thúc bi thảm'),
(11,2,11,'Về tác phẩm "Lão Hạc" của Nam Cao:',
 'Lão Hạc bán con chó Vàng để giữ lại tiền cho con trai',
 'Lão Hạc chết vì bệnh nặng không có tiền chữa trị',
 'Câu chuyện được kể từ góc nhìn của ông giáo làng',
 'Tác phẩm phản ánh số phận bi thảm của người nông dân',
 'Đ,S,Đ,Đ',
 'Đúng: để dành tiền và mảnh vườn cho con','Sai: lão ăn bả chó để tự tử','Đúng: ông giáo là người kể chuyện','Đúng: hiện thực tàn khốc trước cách mạng'),
-- ChuDe 12: Tho moi
(12,2,11,'Về bài thơ "Vội vàng" của Xuân Diệu:',
 'Bài thơ thể hiện khát vọng sống mãnh liệt, tận hưởng tuổi xuân',
 'Tác giả kêu gọi mọi người sống chậm, thong thả',
 'Xuân Diệu được mệnh danh là "ông hoàng thơ tình" Việt Nam',
 'Bài thơ thuộc phong trào Thơ mới (1932-1945)',
 'Đ,S,Đ,Đ',
 'Đúng: "Mau đi thôi! Mùa chưa ngả chiều hôm"','Sai: ngược lại, kêu gọi sống gấp','Đúng: biệt danh nổi tiếng của Xuân Diệu','Đúng: phong trào Thơ mới 1932-1945'),
(12,2,11,'Về bài "Đây thôn Vĩ Dạ" của Hàn Mặc Tử:',
 'Tác phẩm được sáng tác khi tác giả đang mắc bệnh phong',
 'Vĩ Dạ là một làng ở Hà Nội',
 'Bài thơ gồm 3 khổ',
 'Tâm trạng chủ đạo là buồn, cô đơn và khát vọng giao cảm với đời',
 'Đ,S,Đ,Đ',
 'Đúng: viết khi dưỡng bệnh ở Quy Nhơn','Sai: Vĩ Dạ là làng ven Huế','Đúng: 3 khổ, mỗi khổ 4 câu','Đúng: khát khao được sống nhưng bị cô lập'),
-- ChuDe 13: Van te - Truyen tho Nom
(13,2,11,'Về "Văn tế nghĩa sĩ Cần Giuộc" của Nguyễn Đình Chiểu:',
 'Tác phẩm ca ngợi những nghĩa sĩ nông dân đánh Pháp',
 'Đây là một bài thơ Đường luật thất ngôn bát cú',
 'Tác phẩm thuộc thể loại văn tế (điếu văn)',
 'Nguyễn Đình Chiểu là nhà thơ yêu nước lớn của Nam Bộ',
 'Đ,S,Đ,Đ',
 'Đúng: tôn vinh người nông dân anh hùng','Sai: đây là văn tế, không phải thơ Đường','Đúng: văn tế là thể văn dùng để tế lễ người mất','Đúng: sinh sống và sáng tác ở Nam Bộ'),
(13,2,11,'Về "Truyện Kiều" của Nguyễn Du:',
 'Tác phẩm được viết theo thể thơ lục bát',
 'Thúy Kiều bán mình để mua quan tước cho cha',
 'Đoạn trích "Trao duyên" thể hiện nỗi đau khi Kiều nhờ Vân thay mình trả nghĩa Kim Trọng',
 'Truyện Kiều được coi là kiệt tác văn học cổ điển Việt Nam',
 'Đ,S,Đ,Đ',
 'Đúng: thể thơ dân tộc, 3254 câu','Sai: Kiều bán mình để chuộc cha và em thoát nạn','Đúng: đây là bi kịch trao duyên','Đúng: đỉnh cao văn học trung đại Việt Nam'),
-- ChuDe 14: Nghi luan
(14,2,11,'Về đặc điểm của văn nghị luận:',
 'Văn nghị luận dùng lý lẽ và dẫn chứng để thuyết phục',
 'Mục đích chính của văn nghị luận là kể chuyện theo trình tự thời gian',
 'Nghị luận có thể bàn về vấn đề xã hội hoặc văn học',
 'Luận điểm, luận cứ, lập luận là ba yếu tố cơ bản',
 'Đ,S,Đ,Đ',
 'Đúng: đặc trưng cốt lõi của nghị luận','Sai: đó là tự sự, không phải nghị luận','Đúng: nghị luận xã hội và nghị luận văn học','Đúng: ba yếu tố tạo nên bài văn nghị luận'),
(14,2,11,'Về "Chiếu cầu hiền" của Ngô Thì Nhậm:',
 'Đây là thể loại chiếu do vua ban bố',
 'Mục đích là phê phán quan lại tham nhũng',
 'Nhằm kêu gọi người tài ra giúp vua Quang Trung',
 'Tác phẩm thể hiện tư tưởng trọng dụng nhân tài',
 'Đ,S,Đ,Đ',
 'Đúng: chiếu là thể văn do vua ban','Sai: mục đích là cầu hiền tài','Đúng: thuyết phục sĩ phu Bắc Hà ra cộng tác','Đúng: nhân tài là nguyên khí quốc gia'),
-- ChuDe 15: Kich va nghi luan dau the ky XX
(15,2,11,'Về tác phẩm "Vũ Như Tô" của Nguyễn Huy Tưởng:',
 'Đây là tác phẩm thuộc thể loại kịch',
 'Vũ Như Tô là một võ tướng tài ba',
 'Vũ Như Tô chứa đựng mâu thuẫn giữa khát vọng nghệ thuật và thực tế nhân dân',
 'Tác phẩm đặt ra vấn đề về trách nhiệm của nghệ sĩ với xã hội',
 'Đ,S,Đ,Đ',
 'Đúng: kịch lịch sử 5 hồi','Sai: Vũ Như Tô là người thợ thủ công kiến trúc thiên tài','Đúng: bi kịch của người nghệ sĩ tài hoa','Đúng: nghệ thuật vì ai, phục vụ ai'),
(15,2,11,'Về văn học đầu thế kỷ XX:',
 'Đây là giai đoạn giao thoa giữa văn học trung đại và hiện đại',
 'Chữ quốc ngữ không được phổ biến trong giai đoạn này',
 'Báo chí phát triển thúc đẩy sự hình thành văn xuôi quốc ngữ',
 'Phan Châu Trinh chủ trương duy tân, cải cách đất nước',
 'Đ,S,Đ,Đ',
 'Đúng: bước chuyển mình của văn học Việt Nam','Sai: chữ quốc ngữ được phổ biến rộng rãi nhờ báo chí','Đúng: báo chí là môi trường phát triển văn xuôi','Đúng: Phan Châu Trinh theo đường lối cải cách ôn hòa'),
-- ===================== TIENG ANH 12 (ChuDe 16-23) =====================
-- ChuDe 16: Life Stories
(16,3,12,'Read the statements about life stories and biographies:',
 'A biography is a written account of someone''s life written by another person',
 'An autobiography is written by someone other than the subject',
 'The past simple tense is commonly used to narrate past events in life stories',
 'Life stories often include both challenges and achievements',
 'Đ,S,Đ,Đ',
 'True: bio = life written by others','False: autobiography is self-written','True: past simple for completed actions','True: these are common elements of life stories'),
(16,3,12,'About grammar structures used when talking about past experiences:',
 '"Used to + V" describes past habits that no longer happen',
 '"Used to" can describe present habits',
 '"Would + V" can be used to describe repeated past actions',
 'Past perfect is used for actions that happened before another past action',
 'Đ,S,Đ,Đ',
 'True: used to = past habit/state','False: used to is only for the past','True: would = past habit (not state)','True: had + past participle'),
-- ChuDe 17: Urbanisation
(17,3,12,'Read the statements about urbanisation:',
 'Urbanisation refers to the process of people moving from rural to urban areas',
 'Urbanisation always improves quality of life for everyone',
 'Urbanisation can lead to problems such as overcrowding and pollution',
 'Infrastructure development is crucial for sustainable urbanisation',
 'Đ,S,Đ,Đ',
 'True: definition of urbanisation','False: it can also cause inequality and hardship','True: common negative effects','True: roads, water, sanitation are essential'),
(17,3,12,'About reported speech:',
 'In reported speech, present simple usually changes to past simple',
 'Pronouns never change in reported speech',
 '"He said that he was tired" is an example of correct reported speech',
 'Time expressions like "yesterday" change to "the day before" in reported speech',
 'Đ,S,Đ,Đ',
 'True: tense backshift rule','False: pronouns change according to context','True: "I am tired" → "he said he was tired"','True: time expression change rule'),
-- ChuDe 18: The Green Movement
(18,3,12,'Read the statements about the green movement:',
 'The green movement aims to protect the natural environment',
 'Recycling and reducing waste are not part of the green movement',
 'Renewable energy sources such as solar and wind power are promoted',
 'Reducing carbon footprint is one of the key goals',
 'Đ,S,Đ,Đ',
 'True: core aim of environmentalism','False: these are central practices','True: clean energy alternatives','True: to combat climate change'),
(18,3,12,'About modal verbs for advice and obligation:',
 '"Should" is used to give advice or recommendations',
 '"Must" and "have to" always mean exactly the same thing',
 '"Ought to" can be used interchangeably with "should" for advice',
 '"Shouldn''t" expresses that something is not advisable',
 'Đ,S,Đ,Đ',
 'True: should = advice','False: must = internal obligation; have to = external rule','True: ought to ≈ should','True: negative of should'),
-- ChuDe 19: The Mass Media
(19,3,12,'Read the statements about mass media:',
 'Television, radio, and newspapers are traditional forms of mass media',
 'Social media is not considered a form of mass media',
 'Mass media plays an important role in shaping public opinion',
 'The spread of fake news is a serious concern in modern media',
 'Đ,S,Đ,Đ',
 'True: traditional media channels','False: social media is a modern form of mass media','True: media influences beliefs and attitudes','True: misinformation spreads rapidly online'),
(19,3,12,'About the passive voice:',
 'Passive voice is formed with "be + past participle"',
 'The passive voice always emphasizes the person doing the action',
 '"The report was written by the journalist" is in passive voice',
 'Passive voice is commonly used in formal and scientific writing',
 'Đ,S,Đ,Đ',
 'True: am/is/are/was/were + past participle','False: passive hides or de-emphasizes the doer','True: subject receives the action','True: impersonal, objective style'),
-- ChuDe 20: Cultural Identity
(20,3,12,'Read the statements about cultural identity:',
 'Cultural identity refers to a person''s sense of belonging to a particular culture',
 'Globalisation has no impact on cultural identity',
 'Language is an important element of cultural identity',
 'Traditional customs and festivals are part of cultural identity',
 'Đ,S,Đ,Đ',
 'True: definition of cultural identity','False: globalisation can dilute or transform cultures','True: language carries culture and values','True: these preserve and express culture'),
(20,3,12,'About relative clauses:',
 'Defining relative clauses provide essential information about a noun',
 'Non-defining relative clauses are separated from the main clause by commas',
 '"Who" is used to refer to people in relative clauses',
 'Relative clauses always come before the noun they modify',
 'Đ,S,Đ,Đ',
 'True: cannot be omitted without changing meaning','True: they add extra, non-essential information',
 'True: who/whom for people','False: relative clauses come AFTER the noun they modify'),
-- ChuDe 21: Future Jobs
(21,3,12,'Read the statements about future jobs:',
 'Automation and AI are significantly changing the job market',
 'All jobs will eventually be replaced by robots',
 'Soft skills such as communication and teamwork are increasingly valued',
 'STEM fields generally have strong future job prospects',
 'Đ,S,Đ,Đ',
 'True: technology is transforming work','False: many jobs requiring creativity/empathy are hard to automate','True: employers value interpersonal skills','True: high demand for science and technology professionals'),
(21,3,12,'About future tenses in English:',
 '"Will + V" is used for spontaneous decisions made at the moment of speaking',
 '"Be going to + V" is used for unplanned decisions',
 'Present continuous can express planned future arrangements',
 '"Will" can be used to make predictions about the future',
 'Đ,S,Đ,Đ',
 'True: spontaneous = will','False: be going to = planned intention','True: "I am meeting him tomorrow"','True: predictions with will'),
-- ChuDe 22: Economic Reforms
(22,3,12,'Read the statements about economic reforms:',
 'Vietnam''s Đổi Mới policy (1986) is a well-known example of economic reform',
 'Economic reforms always result in decreased living standards',
 'Trade liberalisation and opening markets are common reform strategies',
 'Foreign direct investment often increases following successful economic reforms',
 'Đ,S,Đ,Đ',
 'True: Đổi Mới transformed Vietnam''s economy','False: reforms often raise living standards over time','True: these attract foreign investment','True: reforms improve business environment'),
(22,3,12,'About conditional sentences:',
 'Type 1 conditionals describe real and possible present or future situations',
 'Type 2 conditionals use past simple in the if-clause',
 '"If I were rich, I would travel the world" is a Type 1 conditional',
 'Type 3 conditionals refer to unreal situations in the past',
 'Đ,S,Đ,Đ',
 'True: If + present simple, will + V','True: If + past simple, would + V','False: it is Type 2 (hypothetical present)','True: If + past perfect, would have + past participle'),
-- ChuDe 23: International Integration
(23,3,12,'Read the statements about international integration:',
 'ASEAN is an example of regional international integration',
 'International integration means countries become completely isolated',
 'Trade agreements are important tools for promoting international integration',
 'Cultural exchange is one benefit of international integration',
 'Đ,S,Đ,Đ',
 'True: ASEAN promotes regional cooperation','False: integration is the opposite of isolation','True: trade agreements reduce barriers','True: people share cultures and values'),
(23,3,12,'About connectors and complex sentences:',
 '"However" is used to express contrast between two ideas',
 '"Therefore" is used to show cause and effect (result)',
 '"Although" introduces a concessive clause showing contrast',
 '"Furthermore" is used to show contradiction',
 'Đ,Đ,Đ,S',
 'True: However = contrast','True: Therefore = result/conclusion','True: Although = even though','False: Furthermore = addition, not contradiction'),
-- ===================== VAT LY 10 (ChuDe 24-30) =====================
-- ChuDe 24: Dong hoc chat diem
(24,4,10,'Về chuyển động thẳng đều:',
 'Vận tốc không thay đổi theo thời gian',
 'Gia tốc của chuyển động thẳng đều bằng 1',
 'Quãng đường đi được tỉ lệ thuận với thời gian (s = v.t)',
 'Đồ thị v-t là đường thẳng song song với trục thời gian',
 'Đ,S,Đ,Đ',
 'Đúng: vận tốc không đổi là đặc trưng','Sai: gia tốc = 0, không phải 1','Đúng: s tỉ lệ bậc nhất với t','Đúng: v = const trên đồ thị v-t'),
(24,4,10,'Về chuyển động thẳng nhanh dần đều:',
 'Gia tốc a > 0 và không đổi',
 'Vận tốc giảm dần theo thời gian',
 'Công thức vận tốc: v = v₀ + at',
 'Quãng đường: s = v₀t + ½at²',
 'Đ,S,Đ,Đ',
 'Đúng: a>0 và không đổi','Sai: vận tốc tăng dần','Đúng: công thức tổng quát','Đúng: công thức quãng đường'),
-- ChuDe 25: Dong luc hoc chat diem
(25,4,10,'Về định luật II Newton (F = ma):',
 'Lực và gia tốc cùng chiều với nhau',
 'Nếu khối lượng tăng gấp đôi, gia tốc giảm còn một nửa (lực không đổi)',
 'Khi hợp lực bằng 0, vật đứng yên hoặc chuyển động thẳng đều',
 'Đơn vị của lực trong hệ SI là Newton (N)',
 'Đ,Đ,Đ,Đ',
 'Đúng: F⃗ cùng chiều a⃗','Đúng: a=F/m','Đúng: định luật I Newton','Đúng: 1N = 1kg·m/s²'),
(25,4,10,'Về lực ma sát:',
 'Lực ma sát nghỉ ngăn không cho vật trượt',
 'Lực ma sát trượt luôn lớn hơn lực ma sát nghỉ cực đại',
 'Hệ số ma sát là đại lượng không có đơn vị',
 'Lực ma sát trượt luôn ngược chiều chuyển động tương đối',
 'Đ,S,Đ,Đ',
 'Đúng: ma sát nghỉ cân bằng lực kéo','Sai: ma sát trượt nhỏ hơn ma sát nghỉ cực đại','Đúng: μ = Fms/N là tỉ số không đơn vị','Đúng: đặc trưng quan trọng của ma sát'),
-- ChuDe 26: Can bang va chuyen dong vat ran
(26,4,10,'Điều kiện cân bằng của vật rắn:',
 'Hợp lực tác dụng lên vật bằng 0',
 'Chỉ cần hợp lực bằng 0 là đủ để vật cân bằng',
 'Tổng momen lực đối với bất kỳ điểm nào cũng bằng 0',
 'Vật có thể quay đều nếu tổng momen bằng 0',
 'Đ,S,Đ,Đ',
 'Đúng: điều kiện thứ nhất','Sai: cần thêm điều kiện tổng momen = 0','Đúng: điều kiện thứ hai','Đúng: quay đều cũng là cân bằng động'),
(26,4,10,'Về momen lực M = F.d:',
 'Cánh tay đòn d là khoảng cách vuông góc từ trục quay đến đường tác dụng của lực',
 'Đơn vị của momen lực là N (Newton)',
 'Momen lực càng lớn khi cánh tay đòn càng dài (lực không đổi)',
 'Momen lực là đại lượng có thể dương hoặc âm tùy chiều quay',
 'Đ,S,Đ,Đ',
 'Đúng: định nghĩa cánh tay đòn','Sai: đơn vị là N.m (Newton.mét)','Đúng: M tỉ lệ thuận với d','Đúng: chiều quay quy ước dương/âm'),
-- ChuDe 27: Cac dinh luat bao toan
(27,4,10,'Về bảo toàn động lượng:',
 'Động lượng hệ được bảo toàn khi không có ngoại lực tác dụng',
 'Công thức động lượng: p⃗ = m.v⃗',
 'Trong va chạm đàn hồi hoàn toàn, cả động lượng và động năng đều bảo toàn',
 'Đơn vị của động lượng là kg·m²/s',
 'Đ,Đ,Đ,S',
 'Đúng: định luật bảo toàn động lượng','Đúng: công thức định nghĩa','Đúng: va chạm đàn hồi hoàn toàn','Sai: đơn vị là kg·m/s'),
(27,4,10,'Về bảo toàn cơ năng:',
 'Động năng Wđ = ½mv²',
 'Thế năng trọng trường Wt = mgh',
 'Cơ năng luôn bảo toàn trong mọi trường hợp',
 'Khi không có ma sát, cơ năng của vật được bảo toàn',
 'Đ,Đ,S,Đ',
 'Đúng: công thức động năng','Đúng: công thức thế năng','Sai: ma sát làm mất cơ năng','Đúng: bảo toàn khi chỉ có lực thế'),
-- ChuDe 28: Chat khi - Dinh luat chat khi
(28,4,10,'Định luật Boyle (quá trình đẳng nhiệt, T = const):',
 'Tích pV = hằng số khi nhiệt độ không đổi',
 'Khi áp suất tăng, thể tích cũng tăng',
 'Đồ thị p-V của quá trình đẳng nhiệt là đường hyperbol',
 'p₁V₁ = p₂V₂ là biểu thức của định luật Boyle',
 'Đ,S,Đ,Đ',
 'Đúng: định luật Boyle','Sai: áp suất và thể tích tỉ lệ nghịch','Đúng: quan hệ nghịch biến tạo hyperbol','Đúng: biểu thức áp dụng'),
(28,4,10,'Định luật Charles (quá trình đẳng áp, p = const):',
 'V/T = hằng số khi áp suất không đổi',
 'Khi nhiệt độ tăng, thể tích giảm',
 'Nhiệt độ trong các định luật khí lý tưởng phải dùng đơn vị Kelvin',
 '0°C tương đương 273 K',
 'Đ,S,Đ,Đ',
 'Đúng: định luật Charles','Sai: nhiệt độ và thể tích tỉ lệ thuận','Đúng: T(K) = T(°C) + 273','Đúng: 0 + 273 = 273'),
-- ChuDe 29: Co so nhiet dong luc hoc
(29,4,10,'Về nguyên lý I nhiệt động lực học (ΔU = Q + A):',
 'Nội năng của hệ tăng khi nhận nhiệt và nhận công',
 'Nhiệt lượng truyền cho hệ luôn làm giảm nội năng',
 'Công thực hiện lên hệ (A>0) làm tăng nội năng',
 'Nguyên lý I là dạng tổng quát của định luật bảo toàn năng lượng',
 'Đ,S,Đ,Đ',
 'Đúng: Q>0 và A>0 làm ΔU>0','Sai: Q>0 làm tăng nội năng','Đúng: A>0 là công bên ngoài thực hiện lên hệ','Đúng: năng lượng không tự nhiên sinh ra hay mất đi'),
(29,4,10,'Về nguyên lý II nhiệt động lực học:',
 'Nhiệt không thể tự truyền từ vật lạnh hơn sang vật nóng hơn',
 'Động cơ nhiệt có thể đạt hiệu suất 100%',
 'Hiệu suất động cơ nhiệt luôn nhỏ hơn 1 (nhỏ hơn 100%)',
 'Entropi là đại lượng đặc trưng cho mức độ mất trật tự của hệ',
 'Đ,S,Đ,Đ',
 'Đúng: phát biểu Clausius','Sai: không thể có động cơ vĩnh cửu loại 2','Đúng: luôn có tổn thất','Đúng: ΔS ≥ 0 cho quá trình tự nhiên'),
-- ChuDe 30: Chat long - Chat ran - Su chuyen the
(30,4,10,'Về sự nở vì nhiệt của các chất:',
 'Chất rắn, lỏng và khí đều nở ra khi nhiệt độ tăng',
 'Nước có tính chất bất thường: co lại khi hạ nhiệt từ 4°C xuống 0°C',
 'Sự nở vì nhiệt của khí lớn hơn nhiều so với chất rắn',
 'Thanh ray đường sắt cần có khe hở để tránh cong vênh khi nở nhiệt',
 'Đ,S,Đ,Đ',
 'Đúng: nguyên tắc chung','Sai: nước nở ra khi hạ nhiệt từ 4°C xuống 0°C','Đúng: khí nở nhiều hơn rất nhiều','Đúng: ứng dụng thực tế quan trọng'),
(30,4,10,'Về sự chuyển thể của các chất:',
 'Nóng chảy là quá trình chuyển từ thể rắn sang thể lỏng',
 'Ngưng tụ là quá trình chuyển từ thể lỏng sang thể hơi',
 'Bay hơi có thể xảy ra ở nhiệt độ bất kỳ (dưới nhiệt độ sôi)',
 'Nhiệt độ sôi của chất lỏng phụ thuộc vào áp suất bên ngoài',
 'Đ,S,Đ,Đ',
 'Đúng: rắn → lỏng khi nhận nhiệt','Sai: ngưng tụ là hơi → lỏng; lỏng → hơi là bay hơi','Đúng: bay hơi xảy ra trên bề mặt thoáng','Đúng: áp suất cao làm tăng nhiệt độ sôi'),
-- ===================== HOA HOC 11 (ChuDe 31-38) =====================
-- ChuDe 31: Su dien li
(31,5,11,'Về sự điện li và chất điện li:',
 'Chất điện li là chất khi tan trong nước phân li ra ion',
 'NaCl là chất điện li yếu',
 'CH₃COOH là chất điện li yếu (điện li một phần)',
 'Dung dịch điện li dẫn điện được do có ion tự do',
 'Đ,S,Đ,Đ',
 'Đúng: định nghĩa chất điện li','Sai: NaCl là chất điện li mạnh','Đúng: axit axetic điện li một phần','Đúng: ion di chuyển tạo dòng điện'),
(31,5,11,'Về pH của dung dịch:',
 'Dung dịch có pH = 7 ở 25°C là dung dịch trung tính',
 'Dung dịch axit mạnh có pH > 7',
 'Dung dịch bazơ có pH > 7',
 'Công thức tính: pH = -log[H⁺]',
 'Đ,S,Đ,Đ',
 'Đúng: [H⁺]=[OH⁻]=10⁻⁷ ở 25°C','Sai: axit có pH < 7','Đúng: bazơ có [OH⁻]>[H⁺] nên pH>7','Đúng: định nghĩa pH'),
-- ChuDe 32: Nito va hop chat
(32,5,11,'Về nitơ và hợp chất của nitơ:',
 'Khí N₂ chiếm khoảng 78% thể tích không khí',
 'Khí NH₃ không tan trong nước',
 'HNO₃ đặc nguội thụ động hóa Fe và Al',
 'NH₃ có tính khử (bị oxi hóa bởi O₂ khi đốt cháy)',
 'Đ,S,Đ,Đ',
 'Đúng: thành phần không khí','Sai: NH₃ tan rất nhiều trong nước','Đúng: màng oxit bền bảo vệ kim loại','Đúng: 4NH₃ + 3O₂ → 2N₂ + 6H₂O'),
(32,5,11,'Về axit nitric HNO₃:',
 'HNO₃ đặc có tính oxi hóa mạnh',
 'HNO₃ loãng không phản ứng được với các kim loại',
 'Muối nitrat của hầu hết các kim loại đều tan trong nước',
 'Cu tác dụng với HNO₃ loãng tạo khí NO (không màu)',
 'Đ,S,Đ,Đ',
 'Đúng: HNO₃ đặc oxi hóa nhiều chất','Sai: HNO₃ loãng phản ứng với nhiều kim loại','Đúng: muối nitrat đa số tan','Đúng: 3Cu + 8HNO₃ loãng → 3Cu(NO₃)₂ + 2NO + 4H₂O'),
-- ChuDe 33: Photpho va hop chat
(33,5,11,'Về photpho và các dạng tồn tại:',
 'Photpho tồn tại ở dạng thù hình: P trắng và P đỏ',
 'P trắng bền hơn P đỏ ở nhiệt độ thường',
 'H₃PO₄ là axit ba nấc (ba giai đoạn điện li)',
 'Phân lân cung cấp nguyên tố photpho cho cây trồng',
 'Đ,S,Đ,Đ',
 'Đúng: hai dạng thù hình phổ biến','Sai: P đỏ bền hơn P trắng','Đúng: có 3 nhóm -OH có thể tách H⁺','Đúng: tăng năng suất cây trồng'),
(33,5,11,'Về hợp chất của photpho:',
 'P₂O₅ phản ứng mạnh với nước tạo axit photphoric',
 'Ca₃(PO₄)₂ tan tốt trong nước thường',
 'Phân supephotphat đơn chứa Ca(H₂PO₄)₂ và CaSO₄',
 'Photpho trắng rất độc và tự bốc cháy trong không khí',
 'Đ,S,Đ,Đ',
 'Đúng: P₂O₅ + 3H₂O → 2H₃PO₄','Sai: Ca₃(PO₄)₂ hầu như không tan','Đúng: thành phần phân supephotphat','Đúng: P trắng bảo quản trong nước'),
-- ChuDe 34: Cacbon - Silic
(34,5,11,'Về cacbon và các dạng thù hình:',
 'Cacbon có ba dạng thù hình phổ biến: kim cương, than chì và fullerene',
 'Kim cương là vật liệu dẫn điện tốt',
 'CO₂ là khí gây hiệu ứng nhà kính',
 'Than chì mềm và có khả năng dẫn điện',
 'Đ,S,Đ,Đ',
 'Đúng: ba dạng thù hình chính','Sai: kim cương là chất cách điện','Đúng: CO₂ giữ nhiệt trong khí quyển','Đúng: than chì dùng làm điện cực'),
(34,5,11,'Về silic và hợp chất:',
 'Silic là á kim, là chất bán dẫn quan trọng',
 'SiO₂ tan dễ dàng trong nước thường',
 'SiO₂ tan được trong dung dịch HF',
 'Silic được ứng dụng rộng rãi trong công nghệ bán dẫn và điện tử',
 'Đ,S,Đ,Đ',
 'Đúng: Si là nền tảng của công nghệ hiện đại','Sai: SiO₂ không tan trong nước','Đúng: SiO₂ + 4HF → SiF₄ + 2H₂O','Đúng: chip điện tử, pin mặt trời'),
-- ChuDe 35: Dai cuong hoa hoc huu co
(35,5,11,'Về hóa học hữu cơ:',
 'Hợp chất hữu cơ nhất thiết phải chứa nguyên tố carbon',
 'CO₂ và CO là hợp chất hữu cơ',
 'Liên kết cộng hóa trị là liên kết phổ biến trong hợp chất hữu cơ',
 'Butlerov là người đề ra thuyết cấu tạo hóa học',
 'Đ,S,Đ,Đ',
 'Đúng: C là nguyên tố đặc trưng của hữu cơ','Sai: CO₂, CO là hợp chất vô cơ','Đúng: liên kết C-C và C-H phổ biến','Đúng: thuyết nền tảng của hóa hữu cơ hiện đại'),
(35,5,11,'Về đồng phân và phân tích cấu tạo:',
 'Đồng phân là các chất có cùng công thức phân tử nhưng khác cấu tạo',
 'Mạch cacbon chỉ có thể ở dạng mạch thẳng',
 'Phân tích nguyên tố giúp xác định % khối lượng từng nguyên tố trong hợp chất',
 'Công thức chung của ankan là CₙH₂ₙ₊₂',
 'Đ,S,Đ,Đ',
 'Đúng: cùng CTPT, khác CTCT','Sai: mạch cacbon có thể thẳng, nhánh hoặc vòng','Đúng: cơ sở xác định công thức phân tử','Đúng: ankan no, chỉ có liên kết đơn'),
-- ChuDe 36: Hidrocacbon no (Ankan)
(36,5,11,'Về ankan (hidrocacbon no):',
 'Ankan chỉ có liên kết đơn C-C và C-H trong phân tử',
 'Metan (CH₄) là ankan có 2 nguyên tử carbon',
 'Ankan khó tan trong nước nhưng tan được trong dung môi hữu cơ',
 'Phản ứng thế halogen (halogen hóa) là phản ứng đặc trưng của ankan',
 'Đ,S,Đ,Đ',
 'Đúng: ankan là hidrocacbon no','Sai: CH₄ chỉ có 1 nguyên tử C','Đúng: không phân cực nên tan trong dung môi hữu cơ','Đúng: CH₄ + Cl₂ → CH₃Cl + HCl'),
(36,5,11,'Về tính chất hóa học của ankan:',
 'Etan có công thức phân tử C₂H₆',
 'Ankan phản ứng dễ dàng với dung dịch KMnO₄ ở điều kiện thường',
 'Khi đốt cháy hoàn toàn ankan tạo CO₂ và H₂O',
 'Phản ứng cracking ankan tạo ra các hidrocacbon nhỏ hơn',
 'Đ,S,Đ,Đ',
 'Đúng: C₂H₂×₂+₂ = C₂H₆','Sai: ankan trơ với KMnO₄ ở điều kiện thường','Đúng: phản ứng cháy hoàn toàn','Đúng: ứng dụng trong công nghiệp dầu mỏ'),
-- ChuDe 37: Hidrocacbon khong no
(37,5,11,'Về anken (có một liên kết đôi C=C):',
 'Anken có một liên kết đôi C=C trong phân tử',
 'Etilen (etylen) có công thức phân tử C₃H₆',
 'Anken tham gia phản ứng cộng đặc trưng',
 'Etilen làm mất màu dung dịch brom (Br₂)',
 'Đ,S,Đ,Đ',
 'Đúng: đặc trưng của anken','Sai: etilen là C₂H₄; C₃H₆ là propilen','Đúng: liên kết đôi dễ bị cộng','Đúng: CH₂=CH₂ + Br₂ → CH₂Br-CH₂Br'),
(37,5,11,'Về ankin (có một liên kết ba C≡C):',
 'Ankin có một liên kết ba C≡C trong phân tử',
 'Axetilen không cháy được trong không khí',
 'Axetilen có công thức CH≡CH (C₂H₂)',
 'Ankin tham gia được cả phản ứng cộng và phản ứng thế H bằng ion kim loại',
 'Đ,S,Đ,Đ',
 'Đúng: đặc trưng của ankin','Sai: axetilen cháy cho ngọn lửa nhiệt độ rất cao','Đúng: công thức axetilen','Đúng: tính chất đặc trưng của ankin đầu mạch'),
-- ChuDe 38: Ancol - Phenol - Andehit - Axit cacboxylic
(38,5,11,'Về ancol:',
 'Ancol chứa nhóm chức -OH liên kết với gốc hidrocacbon',
 'Etanol (C₂H₅OH) không tan trong nước',
 'Ancol phản ứng với natri kim loại giải phóng khí H₂',
 'Nhiệt độ sôi của ancol cao hơn hidrocacbon có phân tử khối tương đương',
 'Đ,S,Đ,Đ',
 'Đúng: cấu tạo đặc trưng của ancol','Sai: etanol tan vô hạn trong nước','Đúng: 2C₂H₅OH + 2Na → 2C₂H₅ONa + H₂↑','Đúng: liên kết hiđro làm tăng nhiệt độ sôi'),
(38,5,11,'Về axit cacboxylic:',
 'Axit cacboxylic chứa nhóm chức -COOH',
 'Axit axetic (CH₃COOH) là axit mạnh',
 'Axit axetic phản ứng với NaOH tạo muối natri axetat và nước',
 'Axit fomic (HCOOH) có tính khử do chứa nhóm -CHO trong cấu tạo',
 'Đ,S,Đ,Đ',
 'Đúng: nhóm chức của axit cacboxylic','Sai: CH₃COOH là axit yếu, điện li một phần','Đúng: CH₃COOH + NaOH → CH₃COONa + H₂O','Đúng: HCOOH vừa có tính axit vừa có tính khử'),
-- ===================== SINH HOC 9 (ChuDe 39-46) =====================
-- ChuDe 39: Di truyen va bien di - Menden
(39,6,9,'Về các thí nghiệm của Menđen:',
 'Menđen thực hiện thí nghiệm lai trên cây đậu Hà Lan (Pisum sativum)',
 'Menđen là nhà khoa học người Mỹ',
 'Tỉ lệ phân li 3:1 xuất hiện ở đời F₂ khi lai một tính trạng',
 'Gen trội át hoàn toàn gen lặn trong trường hợp trội hoàn toàn',
 'Đ,S,Đ,Đ',
 'Đúng: đối tượng nghiên cứu của Menđen','Sai: Menđen là tu sĩ người Áo (hiện là CH Séc)','Đúng: quy luật phân li','Đúng: Aa biểu hiện tính trạng trội giống AA'),
(39,6,9,'Về các khái niệm di truyền học cơ bản:',
 'Kiểu gen Aa là thể dị hợp về một cặp gen',
 'Kiểu gen AA và Aa đều biểu hiện kiểu hình trội (nếu A trội hoàn toàn so với a)',
 'Quy luật phân li độc lập áp dụng khi các gen nằm trên cùng một cặp NST',
 'Lai hai cặp tính trạng F₁ (AaBb × AaBb) cho tỉ lệ kiểu hình 9:3:3:1',
 'Đ,Đ,S,Đ',
 'Đúng: dị hợp là có hai alen khác nhau','Đúng: A trội nên AA và Aa đều biểu hiện trội','Sai: phải ở hai cặp NST khác nhau','Đúng: tỉ lệ kinh điển của Menđen'),
-- ChuDe 40: NST va di truyen lien ket
(40,6,9,'Về nhiễm sắc thể (NST):',
 'NST là vật chất di truyền nằm trong nhân tế bào',
 'Người bình thường có 46 cặp NST trong mỗi tế bào',
 'Gen liên kết giới tính thường nằm trên NST giới tính X',
 'Nguyên phân tạo ra 2 tế bào con có bộ NST giống tế bào mẹ (2n)',
 'Đ,S,Đ,Đ',
 'Đúng: NST nằm trong nhân','Sai: người có 23 cặp (46 chiếc) NST','Đúng: gen liên kết X phổ biến','Đúng: nguyên phân bảo toàn bộ NST'),
(40,6,9,'Về giảm phân và di truyền liên kết:',
 'Giảm phân xảy ra ở tế bào sinh dục chín (sinh tinh, sinh trứng)',
 'Giảm phân tạo ra 2 tế bào con với bộ NST 2n',
 'Trao đổi chéo (hoán vị gen) xảy ra ở kỳ đầu của giảm phân I',
 'Di truyền liên kết làm hạn chế tổ hợp tự do của các gen',
 'Đ,S,Đ,Đ',
 'Đúng: xảy ra trong cơ quan sinh dục','Sai: giảm phân tạo 4 tế bào con có bộ NST n','Đúng: trao đổi chéo tạo đa dạng di truyền','Đúng: các gen cùng NST có xu hướng di truyền cùng nhau'),
-- ChuDe 41: ADN - Gen - Ma di truyen
(41,6,9,'Về cấu trúc và chức năng của ADN:',
 'ADN là phân tử mang thông tin di truyền của tế bào',
 'ADN được cấu tạo từ các đơn phân là axit amin',
 'Nguyên tắc bổ sung trong ADN: A liên kết với T, G liên kết với X',
 'Quá trình phiên mã tổng hợp phân tử mARN từ khuôn ADN',
 'Đ,S,Đ,Đ',
 'Đúng: ADN chứa thông tin di truyền','Sai: ADN cấu tạo từ nuclêôtit, không phải axit amin','Đúng: nguyên tắc Watson-Crick','Đúng: phiên mã xảy ra trong nhân'),
(41,6,9,'Về mã di truyền và tổng hợp protein:',
 'Mã di truyền là bộ ba nuclêôtit (codon) mã hóa cho một axit amin',
 'Mỗi axit amin chỉ được mã hóa bởi đúng một bộ ba duy nhất',
 'Ribosome là nơi diễn ra quá trình dịch mã (tổng hợp protein)',
 'tARN có chức năng vận chuyển axit amin đến ribosome',
 'Đ,S,Đ,Đ',
 'Đúng: codon = bộ ba mã hóa','Sai: mã di truyền có tính suy biến (nhiều bộ ba → 1 axit amin)','Đúng: ribosome là "nhà máy" tổng hợp protein','Đúng: tARN mang axit amin tương ứng với anticodon'),
-- ChuDe 42: Bien di - Dot bien
(42,6,9,'Về đột biến và biến dị:',
 'Đột biến gen là sự thay đổi trong cấu trúc của gen',
 'Thường biến là biến dị di truyền được cho thế hệ sau',
 'Đột biến có thể có lợi, có hại hoặc trung tính với sinh vật',
 'Tia X và tia UV là các tác nhân vật lý có thể gây đột biến gen',
 'Đ,S,Đ,Đ',
 'Đúng: thay thế, thêm, mất cặp nuclêôtit','Sai: thường biến không di truyền, do môi trường','Đúng: phụ thuộc vào loại đột biến và môi trường','Đúng: tác nhân gây đột biến vật lý'),
(42,6,9,'Về đột biến nhiễm sắc thể:',
 'Đột biến NST bao gồm đột biến cấu trúc và đột biến số lượng NST',
 'Thể đa bội có bộ NST là 2n (lưỡng bội)',
 'Colchicine cản trở hình thành thoi phân bào, gây ra đa bội',
 'Đa bội thể phổ biến hơn ở thực vật so với động vật bậc cao',
 'Đ,S,Đ,Đ',
 'Đúng: hai loại đột biến NST chính','Sai: đa bội là 3n, 4n... (nhiều hơn 2n)','Đúng: colchicine ức chế hình thành thoi vô sắc','Đúng: động vật bậc cao ít chịu được đa bội'),
-- ChuDe 43: Di truyen hoc nguoi
(43,6,9,'Về bệnh di truyền ở người:',
 'Bệnh Down do thừa một NST số 21 (thể ba nhiễm ở cặp 21)',
 'Bệnh mù màu đỏ-lục do gen trội nằm trên NST thường',
 'Phương pháp nghiên cứu phả hệ giúp xác định tính trội hay lặn của tính trạng',
 'Nam giới (XY) dễ biểu hiện bệnh do gen lặn liên kết X hơn nữ giới',
 'Đ,S,Đ,Đ',
 'Đúng: 47 NST, thể ba ở cặp 21','Sai: gen lặn liên kết X, không phải NST thường','Đúng: theo dõi nhiều thế hệ trong gia đình','Đúng: nam chỉ có 1 X nên gen lặn biểu hiện ngay'),
(43,6,9,'Về hội chứng di truyền ở người:',
 'Hội chứng Turner (XO) gặp ở người nữ, gây vô sinh',
 'Hội chứng Klinefelter (XXY) gặp ở người nữ',
 'Bệnh máu khó đông (hemophilia) do gen lặn liên kết NST X',
 'Liệu pháp gen là kỹ thuật hiện đại trong điều trị bệnh di truyền',
 'Đ,S,Đ,Đ',
 'Đúng: XO thiếu 1 NST X','Sai: XXY gặp ở người nam (nam thừa 1 X)','Đúng: H là gen lặn trên X, nữ XX dễ bị che khuất','Đúng: bổ sung gen lành thay thế gen bệnh'),
-- ChuDe 44: Ung dung di truyen hoc
(44,6,9,'Về ứng dụng di truyền học trong chọn giống:',
 'Lai xa kết hợp đa bội hóa giúp tạo giống cây trồng mới',
 'Gây đột biến nhân tạo luôn tạo ra các đột biến có lợi',
 'Colchicine thường được dùng để tạo thể đa bội trong chọn giống',
 'Công nghệ ADN tái tổ hợp được ứng dụng để sản xuất insulin',
 'Đ,S,Đ,Đ',
 'Đúng: lúa mì, cải dầu được tạo theo cách này','Sai: hầu hết đột biến là có hại hoặc trung tính','Đúng: gây đa bội nhân tạo trong chọn giống thực vật','Đúng: vi khuẩn mang gen insulin người sản xuất thuốc'),
(44,6,9,'Về ưu thế lai và tạo giống thuần chủng:',
 'Tự thụ phấn bắt buộc qua nhiều thế hệ giúp tạo dòng thuần chủng',
 'Ưu thế lai thường cao nhất ở thế hệ F₁',
 'Vi khuẩn có thể được dùng làm vật chủ trong kỹ thuật di truyền',
 'Vi khuẩn không thể mang và biểu hiện gen của sinh vật khác',
 'Đ,Đ,Đ,S',
 'Đúng: tự thụ phấn tăng tỉ lệ đồng hợp','Đúng: F₁ có ưu thế lai cao nhất, sau đó giảm','Đúng: E.coli là vật chủ phổ biến trong kỹ thuật gen','Sai: vi khuẩn có thể mang và biểu hiện gen ngoại lai'),
-- ChuDe 45: Moi truong va nhan to sinh thai
(45,6,9,'Về môi trường và các nhân tố sinh thái:',
 'Nhân tố sinh thái gồm nhân tố vô sinh và nhân tố hữu sinh',
 'Ánh sáng, nhiệt độ, nước là các nhân tố hữu sinh',
 'Giới hạn sinh thái là khoảng giá trị của nhân tố sinh thái mà sinh vật có thể tồn tại',
 'Sinh vật có khả năng thích nghi với sự biến đổi của môi trường',
 'Đ,S,Đ,Đ',
 'Đúng: phân loại cơ bản các nhân tố sinh thái','Sai: đây là nhân tố vô sinh (vật lý)','Đúng: gồm khoảng thuận lợi và khoảng chịu đựng','Đúng: tiến hóa và thích nghi'),
(45,6,9,'Về các mối quan hệ sinh thái giữa các loài:',
 'Cây ưa bóng quang hợp hiệu quả hơn trong điều kiện ánh sáng yếu',
 'Nhiệt độ không ảnh hưởng đến tốc độ phản ứng sinh hóa trong cơ thể',
 'Quan hệ cộng sinh đem lại lợi ích cho cả hai loài',
 'Quan hệ kí sinh gây hại cho sinh vật bị kí sinh (vật chủ)',
 'Đ,S,Đ,Đ',
 'Đúng: điểm bù ánh sáng thấp','Sai: nhiệt độ ảnh hưởng lớn đến enzyme và trao đổi chất','Đúng: ví dụ: nấm và tảo trong địa y','Đúng: kí sinh lấy chất dinh dưỡng từ vật chủ'),
-- ChuDe 46: He sinh thai
(46,6,9,'Về hệ sinh thái và chuỗi thức ăn:',
 'Hệ sinh thái gồm quần xã sinh vật và môi trường sống của chúng',
 'Thực vật xanh là sinh vật tiêu thụ bậc 1 trong hệ sinh thái',
 'Chuỗi thức ăn bắt đầu từ sinh vật sản xuất (cây xanh)',
 'Sự tuần hoàn vật chất và dòng năng lượng là đặc trưng của hệ sinh thái',
 'Đ,S,Đ,Đ',
 'Đúng: định nghĩa hệ sinh thái','Sai: thực vật là sinh vật sản xuất','Đúng: cây xanh → động vật ăn cỏ → động vật ăn thịt','Đúng: hai quá trình cơ bản của hệ sinh thái'),
(46,6,9,'Về dòng năng lượng và bảo vệ môi trường:',
 'Năng lượng mặt trời là nguồn năng lượng đầu vào chủ yếu của hệ sinh thái',
 'Năng lượng được tuần hoàn liên tục trong hệ sinh thái giống như vật chất',
 'Sinh vật phân hủy có vai trò phân giải chất hữu cơ thành chất vô cơ',
 'Bảo vệ đa dạng sinh học là bảo vệ sự cân bằng của hệ sinh thái',
 'Đ,S,Đ,Đ',
 'Đúng: quang hợp là cửa vào năng lượng','Sai: năng lượng mất dần qua các bậc dinh dưỡng, không tuần hoàn','Đúng: vi khuẩn và nấm phân giải xác chết','Đúng: mất loài gây mất ổn định hệ sinh thái'),
-- ===================== LICH SU 8 (ChuDe 47-54) =====================
-- ChuDe 47: Chau Au va nuoc My cuoi XVIII dau XX
(47,7,8,'Về Cách mạng tư sản Pháp 1789:',
 'Cách mạng Pháp 1789 đã lật đổ chế độ phong kiến quân chủ',
 'Khẩu hiệu "Tự do - Bình đẳng - Bác ái" là của Cách mạng Nga',
 'Cách mạng công nghiệp đầu tiên trên thế giới nổ ra ở Anh',
 'Công xã Pari (1871) được coi là nhà nước vô sản đầu tiên trong lịch sử',
 'Đ,S,Đ,Đ',
 'Đúng: lật đổ chế độ phong kiến','Sai: đây là khẩu hiệu của Cách mạng Pháp','Đúng: Anh là cái nôi của cách mạng công nghiệp','Đúng: tồn tại 72 ngày (1871)'),
(47,7,8,'Về sự phát triển của chủ nghĩa tư bản cuối XIX đầu XX:',
 'Cách mạng công nghiệp Anh (thế kỷ XVIII) là cuộc cách mạng tư sản đầu tiên',
 'Giai cấp tư sản và vô sản ra đời trong quá trình phát triển của CNTB',
 'Các nước tư bản phát triển không tiến hành xâm lược thuộc địa',
 'Mỹ tuyên bố độc lập khỏi Anh vào năm 1776',
 'Đ,Đ,S,Đ',
 'Đúng: cách mạng công nghiệp Anh','Đúng: hai giai cấp đặc trưng của CNTB','Sai: các nước tư bản ra sức xâm chiếm thuộc địa','Đúng: Tuyên ngôn độc lập Mỹ 4/7/1776'),
-- ChuDe 48: A Phi My Latinh cuoi XIX dau XX
(48,7,8,'Về phong trào giải phóng dân tộc ở châu Á:',
 'Duy tân Minh Trị (1868) đã đưa Nhật Bản trở thành cường quốc tư bản',
 'Phong trào Nghĩa Hòa Đoàn ở Trung Quốc giành được thắng lợi hoàn toàn',
 'Cuộc khởi nghĩa Xipay (1857-1859) là phong trào yêu nước ở Ấn Độ',
 'Nhật Bản là nước châu Á tránh được ách đô hộ thực dân',
 'Đ,S,Đ,Đ',
 'Đúng: Nhật hiện đại hóa thành công','Sai: bị liên quân 8 nước đàn áp','Đúng: đòi đuổi thực dân Anh','Đúng: nhờ cải cách Minh Trị'),
(48,7,8,'Về tình hình Mỹ Latinh và châu Phi:',
 'Các nước Mỹ Latinh giành được độc lập từ thực dân trong đầu thế kỷ XIX',
 'Phần lớn châu Phi bị các nước tư bản châu Âu xâu xé thuộc địa cuối XIX',
 'Cuộc đấu tranh của nhân dân Etiopia chống Ý là ví dụ thắng lợi hiếm có',
 'Châu Phi hoàn toàn không có phong trào kháng thực dân nào',
 'Đ,Đ,Đ,S',
 'Đúng: nhiều nước Mỹ Latinh độc lập sau 1810','Đúng: Hội nghị Beclin 1884-1885 phân chia châu Phi','Đúng: Etiopia thắng Ý ở Adua (1896)','Sai: có nhiều phong trào kháng chiến'),
-- ChuDe 49: Chien tranh the gioi thu nhat (1914-1918)
(49,7,8,'Về nguyên nhân và diễn biến Chiến tranh thế giới thứ nhất:',
 'Chiến tranh thế giới thứ nhất bùng nổ vào năm 1914',
 'Mỹ tham chiến ngay từ khi chiến tranh bùng nổ (1914)',
 'Nguyên nhân sâu xa là mâu thuẫn giữa các nước đế quốc về thị trường và thuộc địa',
 'Chiến tranh kết thúc năm 1918 với sự thất bại của phe Liên minh (Đức, Áo-Hung)',
 'Đ,S,Đ,Đ',
 'Đúng: tháng 8/1914','Sai: Mỹ tham chiến năm 1917','Đúng: mâu thuẫn đế quốc là nguyên nhân cơ bản','Đúng: ngày 11/11/1918'),
(49,7,8,'Về hậu quả của Chiến tranh thế giới thứ nhất:',
 'Sự kiện Thái tử Áo-Hung bị ám sát là nguyên nhân trực tiếp gây chiến',
 'Chiến tranh là cuộc chiến tranh phi nghĩa, đế quốc chủ nghĩa',
 'Nga không tham gia Chiến tranh thế giới thứ nhất',
 'Chiến tranh gây ra thương vong và thiệt hại vật chất khổng lồ cho nhân loại',
 'Đ,Đ,S,Đ',
 'Đúng: nguyên nhân trực tiếp tháng 6/1914','Đúng: nhân dân hai phe đều chịu thiệt hại','Sai: Nga tham chiến từ đầu (phe Hiệp ước)','Đúng: hàng chục triệu người chết và bị thương'),
-- ChuDe 50: Cach mang Nga 1917 va Lien Xo
(50,7,8,'Về Cách mạng Nga 1917:',
 'Lênin lãnh đạo Đảng Bônsêvích giành chính quyền trong Cách mạng tháng Mười',
 'Cách mạng Nga 1917 chỉ có một giai đoạn duy nhất',
 'Liên bang Cộng hòa xã hội chủ nghĩa Xô viết (Liên Xô) thành lập năm 1922',
 'Chính sách kinh tế mới NEP giúp khôi phục kinh tế sau chiến tranh',
 'Đ,S,Đ,Đ',
 'Đúng: Lênin và Đảng Bônsêvích','Sai: có hai giai đoạn: Cách mạng tháng Hai và tháng Mười','Đúng: ký thành lập 30/12/1922','Đúng: NEP (1921) thay thế chính sách Cộng sản thời chiến'),
(50,7,8,'Về công cuộc xây dựng Liên Xô (1922-1941):',
 'Cách mạng tháng Hai (1917) lật đổ chế độ Sa hoàng',
 'Cách mạng tháng Mười lật đổ chính quyền lâm thời tư sản',
 'Chính sách công nghiệp hóa biến Liên Xô thành cường quốc công nghiệp',
 'Stalin thực hiện chính sách NEP từ năm 1921',
 'Đ,Đ,Đ,S',
 'Đúng: Cách mạng tháng Hai lật đổ Nga hoàng','Đúng: Cách mạng tháng Mười 25/10/1917','Đúng: qua các kế hoạch 5 năm','Sai: NEP là do Lênin đề xướng'),
-- ChuDe 51: Tu ban giua hai cuoc chien (1919-1939)
(51,7,8,'Về khủng hoảng kinh tế thế giới 1929-1933:',
 'Cuộc khủng hoảng bùng phát đầu tiên ở nước Mỹ (1929)',
 'Chủ nghĩa phát xít lên nắm quyền ở Anh và Pháp',
 'Hitler lên cầm quyền ở Đức vào tháng 1/1933',
 'Chính sách "Mới" (New Deal) của Roosevelt giúp nước Mỹ thoát khỏi khủng hoảng',
 'Đ,S,Đ,Đ',
 'Đúng: xuất phát từ sự sụp đổ thị trường chứng khoán NY','Sai: chủ nghĩa phát xít lên nắm quyền ở Đức và Ý','Đúng: ngày 30/1/1933','Đúng: cải cách kinh tế-xã hội quy mô lớn'),
(51,7,8,'Về chủ nghĩa phát xít và nguy cơ chiến tranh:',
 'Chủ nghĩa phát xít là kẻ thù của hòa bình và dân chủ',
 'Quốc tế Cộng sản ủng hộ chủ nghĩa phát xít để chống Liên Xô',
 'Mặt trận Bình dân ở Pháp (1936) chống lại nguy cơ phát xít',
 'Khủng hoảng kinh tế 1929-1933 là mảnh đất thuận lợi cho chủ nghĩa phát xít',
 'Đ,S,Đ,Đ',
 'Đúng: phát xít gây chiến tranh xâm lược','Sai: Quốc tế Cộng sản kiên quyết chống phát xít','Đúng: liên minh các lực lượng dân chủ','Đúng: khủng hoảng làm dân chúng bất mãn'),
-- ChuDe 52: Chien tranh the gioi thu hai (1939-1945)
(52,7,8,'Về diễn biến Chiến tranh thế giới thứ hai:',
 'Chiến tranh thế giới thứ hai bùng nổ ngày 1/9/1939 khi Đức tấn công Ba Lan',
 'Liên Xô không tham chiến trong Chiến tranh thế giới thứ hai',
 'Trận Xtalingrat (1942-1943) là bước ngoặt làm thay đổi cục diện chiến tranh',
 'Chiến tranh kết thúc năm 1945 với sự thất bại hoàn toàn của phe phát xít',
 'Đ,S,Đ,Đ',
 'Đúng: mốc bùng nổ chiến tranh','Sai: Liên Xô tham chiến từ 22/6/1941','Đúng: bước ngoặt ở mặt trận phía Đông','Đúng: Đức (5/1945) và Nhật (8/1945) đầu hàng'),
(52,7,8,'Về kết thúc Chiến tranh thế giới thứ hai:',
 'Mỹ tham chiến sau sự kiện Nhật tấn công Trân Châu Cảng (12/1941)',
 'Đức đầu hàng không điều kiện vào tháng 5/1945',
 'Nhật Bản đầu hàng trước Đức trong Chiến tranh thế giới thứ hai',
 'Chiến tranh gây ra cái chết cho khoảng 70 triệu người',
 'Đ,Đ,S,Đ',
 'Đúng: 7/12/1941 Nhật tấn công Trân Châu Cảng','Đúng: ngày 9/5/1945','Sai: Đức đầu hàng tháng 5, Nhật đầu hàng tháng 8/1945','Đúng: thảm họa lớn nhất lịch sử nhân loại'),
-- ChuDe 53: Viet Nam tu 1858 den cuoi the ky XIX
(53,7,8,'Về quá trình xâm lược Việt Nam của thực dân Pháp:',
 'Thực dân Pháp nổ súng tấn công Đà Nẵng vào năm 1858',
 'Hiệp ước Nhâm Tuất (1862) nhường 6 tỉnh Nam Kỳ cho Pháp',
 'Phong trào Cần Vương (1885) do vua Hàm Nghi và Tôn Thất Thuyết khởi xướng',
 'Khởi nghĩa Hương Khê (1885-1896) là cuộc khởi nghĩa lớn nhất trong phong trào Cần Vương',
 'Đ,S,Đ,Đ',
 'Đúng: ngày 1/9/1858','Sai: hiệp ước Nhâm Tuất nhường 3 tỉnh miền Đông','Đúng: chiếu Cần Vương kêu gọi chống Pháp','Đúng: lâu dài và quy mô nhất'),
(53,7,8,'Về phong trào kháng Pháp của nhân dân Việt Nam:',
 'Trương Định kiên quyết kháng Pháp ở Nam Kỳ dù triều đình đã ký hòa ước',
 'Triều đình Huế kiên quyết kháng Pháp đến cùng không ký hiệp ước',
 'Nhân dân Việt Nam anh dũng kháng chiến chống thực dân Pháp',
 'Các cuộc khởi nghĩa trong phong trào Cần Vương cuối cùng đều thất bại',
 'Đ,S,Đ,Đ',
 'Đúng: Trương Định không tuân lệnh triều đình','Sai: triều đình liên tiếp ký các hiệp ước đầu hàng','Đúng: tinh thần bất khuất của nhân dân','Đúng: phong trào Cần Vương thất bại cuối thế kỷ XIX'),
-- ChuDe 54: Viet Nam dau the ky XX den 1918
(54,7,8,'Về phong trào yêu nước đầu thế kỷ XX:',
 'Phan Bội Châu khởi xướng phong trào Đông Du đưa thanh niên sang Nhật học',
 'Phong trào Đông Du đưa thanh niên Việt Nam sang Mỹ du học',
 'Đông Kinh nghĩa thục (1907) mở trường học theo lối mới, truyền bá tư tưởng tiến bộ',
 'Phan Châu Trinh chủ trương duy tân, cải cách ôn hòa, không dùng bạo lực',
 'Đ,S,Đ,Đ',
 'Đúng: Phan Bội Châu và phong trào Đông Du','Sai: sang Nhật, không phải Mỹ','Đúng: giáo dục và khai sáng','Đúng: khác với Phan Bội Châu theo đường vũ trang');
-- =========================================================
-- Cau_hoi_tra_loi_ngan: 1 cau / chu de = 54 cau bo sung
-- DapAn: 4 ky tu rieng le (KiTuThu1..4); ky tu thua dung dau cach ' '
-- Mon: 1=Toan10, 2=Van11, 3=Anh12, 4=Ly10, 5=Hoa11, 6=Sinh9, 7=Su8
-- =========================================================
INSERT INTO Cau_hoi_tra_loi_ngan
(ID_ChuDe, ID_MonHoc, ID_KhoiLop,
 NoiDungCauHoi_TracNghiemTraLoiNgan,
 KiTuThu1CuaDapAn_TracNghiemTraLoiNgan,
 KiTuThu2CuaDapAn_TracNghiemTraLoiNgan,
 KiTuThu3CuaDapAn_TracNghiemTraLoiNgan,
 KiTuThu4CuaDapAn_TracNghiemTraLoiNgan,
 HuongDanGiai_TracNghiemTraLoiNgan)
VALUES
-- ===================== TOAN 10 (ChuDe 1-8) =====================
-- ChuDe 1: Menh de va tap hop
(1,1,10,'Tập hợp A = {1, 2, 3, 4} có bao nhiêu tập con? Điền số:','1','6',' ',' ','Số tập con của tập n phần tử = 2ⁿ = 2⁴ = 16'),
-- ChuDe 2: Ham so
(2,1,10,'Hàm số y = x² − 4x + 3 đạt cực tiểu tại x = bao nhiêu? Điền số:','2',' ',' ',' ','x = −b/(2a) = 4/2 = 2; giá trị cực tiểu y_min = −1'),
-- ChuDe 3: Phuong trinh va bat phuong trinh
(3,1,10,'Tổng hai nghiệm của phương trình x² − 7x + 12 = 0 bằng bao nhiêu? (Hệ thức Vi-ét)','7',' ',' ',' ','Theo Vi-ét: x₁+x₂ = −b/a = 7; x₁·x₂ = c/a = 12'),
-- ChuDe 4: He phuong trinh
(4,1,10,'Giải hệ phương trình {x + y = 10 ; x − y = 6}. Nghiệm x = bao nhiêu?','8',' ',' ',' ','Cộng hai pt: 2x = 16 → x = 8; thay lại: y = 2'),
-- ChuDe 5: Vecto trong mat phang
(5,1,10,'Cho A(0;0) và B(3;4). Độ dài đoạn thẳng AB = bao nhiêu? Điền số:','5',' ',' ',' ','|AB| = √(3²+4²) = √25 = 5'),
-- ChuDe 6: Tich vo huong
(6,1,10,'Tích vô hướng a⃗·b⃗ khi a⃗ = (1;0) và b⃗ = (0;1) bằng bao nhiêu? Điền số:','0',' ',' ',' ','a⃗·b⃗ = 1×0 + 0×1 = 0 (hai vectơ vuông góc → tích vô hướng = 0)'),
-- ChuDe 7: Phuong phap toa do trong mat phang
(7,1,10,'Khoảng cách từ gốc O(0;0) đến đường thẳng 3x + 4y − 10 = 0 bằng bao nhiêu?','2',' ',' ',' ','d = |3·0+4·0−10|/√(9+16) = 10/5 = 2'),
-- ChuDe 8: Thong ke va xac suat
(8,1,10,'Gieo một xúc xắc 6 mặt cân đối. Số kết quả thuận lợi để xuất hiện mặt số chẵn là?','3',' ',' ',' ','Mặt chẵn: {2, 4, 6} → 3 kết quả thuận lợi trong 6 kết quả có thể'),
-- ===================== NGU VAN 11 (ChuDe 9-15) =====================
-- ChuDe 9: Tho chu Nom va tho chu Han trung dai
(9,2,11,'Hồ Xuân Hương được mệnh danh là "Bà chúa thơ ____". Điền 1 từ (3 ký tự):','N','ô','m',' ','Hồ Xuân Hương — Bà chúa thơ Nôm, tài năng xuất chúng về thơ chữ Nôm'),
-- ChuDe 10: Van xuoi lang man Viet Nam (1930-1945)
(10,2,11,'Nhân vật chính trong "Chữ người tử tù" của Nguyễn Tuân là Huấn ___. Điền 3 ký tự:','C','a','o',' ','Huấn Cao — nghệ sĩ thư pháp tài hoa, khí phách bất khuất'),
-- ChuDe 11: Van xuoi hien thuc phe phan (1930-1945)
(11,2,11,'"Tắt đèn" của Ngô Tất Tố được xuất bản năm nào? Điền 4 chữ số:','1','9','3','9','"Tắt đèn" (1939) phản ánh cuộc sống thống khổ của nông dân dưới ách sưu thuế'),
-- ChuDe 12: Tho moi (1932-1945)
(12,2,11,'Phong trào Thơ mới chính thức ra đời năm nào? Điền 4 chữ số:','1','9','3','2','Thơ mới xuất hiện 1932 với bài "Tình già" của Phan Khôi trên báo Phụ nữ tân văn'),
-- ChuDe 13: Van te - Truyen tho Nom
(13,2,11,'"Truyện Kiều" của Nguyễn Du gồm bao nhiêu câu thơ lục bát? Điền 4 chữ số:','3','2','5','4','Truyện Kiều gồm 3254 câu thơ lục bát — đỉnh cao văn học trung đại Việt Nam'),
-- ChuDe 14: Nghi luan xa hoi va nghi luan van hoc
(14,2,11,'Ba yếu tố văn nghị luận là luận điểm, luận cứ và "lập ____". Điền 4 ký tự:','l','u','ậ','n','"Lập luận" là cách triển khai, liên kết luận điểm với luận cứ một cách chặt chẽ'),
-- ChuDe 15: Kich va nghi luan dau the ky XX
(15,2,11,'Vở kịch "Vũ Như Tô" của Nguyễn Huy Tưởng gồm bao nhiêu hồi? Điền số:','5',' ',' ',' ','"Vũ Như Tô" gồm 5 hồi, phản ánh bi kịch của người nghệ sĩ tài hoa với khát vọng nghệ thuật'),
-- ===================== TIENG ANH 12 (ChuDe 16-23) =====================
-- ChuDe 16: Unit 1 - Life Stories
(16,3,12,'The simple past tense of the irregular verb "GO" is ____. Fill 4 uppercase letters:','W','E','N','T','Go → Went (irregular verb; past tense used for completed past actions)'),
-- ChuDe 17: Unit 2 - Urbanisation
(17,3,12,'A city with a population of over 10 million is called a ____ city. Fill 4 letters:','M','E','G','A','Mega city = metropolis with 10M+ people; e.g. Tokyo, Shanghai, Ho Chi Minh City'),
-- ChuDe 18: Unit 3 - The Green Movement
(18,3,12,'The 3-letter abbreviation for "Greenhouse Gas" is ____. Fill 3 uppercase letters:','G','H','G',' ','GHG (Greenhouse Gas) traps heat in the atmosphere; CO₂ and CH₄ are major examples'),
-- ChuDe 19: Unit 4 - The Mass Media
(19,3,12,'The 2-letter abbreviation for "Television" is ____. Fill 2 uppercase letters:','T','V',' ',' ','TV (Television) remains one of the most influential mass media channels worldwide'),
-- ChuDe 20: Unit 5 - Cultural Identity
(20,3,12,'The Vietnamese Lunar New Year festival is commonly called ____. Fill 3 uppercase letters:','T','E','T',' ','Tết Nguyên Đán (TẾT) is the most important traditional festival in Vietnam'),
-- ChuDe 21: Unit 6 - Future Jobs
(21,3,12,'The 2-letter abbreviation for "Artificial Intelligence" is ____. Fill 2 uppercase letters:','A','I',' ',' ','AI (Artificial Intelligence) is revolutionizing future jobs and industries worldwide'),
-- ChuDe 22: Unit 7 - Economic Reforms
(22,3,12,'Vietnam''s economic reform policy "Đổi Mới" was launched in year ____. Fill 4 digits:','1','9','8','6','Đổi Mới (Renovation) was introduced at the 6th National Party Congress in 1986'),
-- ChuDe 23: Unit 8 - International Integration
(23,3,12,'ASEAN currently has ____ member countries. Fill 2 digits:','1','0',' ',' ','ASEAN has 10 member states: Brunei, Cambodia, Indonesia, Laos, Malaysia, Myanmar, Philippines, Singapore, Thailand, Vietnam'),
-- ===================== VAT LY 10 (ChuDe 24-30) =====================
-- ChuDe 24: Dong hoc chat diem
(24,4,10,'Ô tô chuyển động thẳng đều v = 20 m/s trong 5 giây. Quãng đường đi được (m) bằng?','1','0','0',' ','s = v × t = 20 × 5 = 100 m'),
-- ChuDe 25: Dong luc hoc chat diem
(25,4,10,'Theo F = m × a: nếu m = 2 kg và a = 5 m/s², lực F (Newton) bằng bao nhiêu?','1','0',' ',' ','F = m × a = 2 × 5 = 10 N (định luật II Newton)'),
-- ChuDe 26: Can bang va chuyen dong vat ran
(26,4,10,'Tổng hợp lực tác dụng lên vật cân bằng bằng bao nhiêu Newton? Điền số:','0',' ',' ',' ','ΣF = 0 N — điều kiện cân bằng: hợp lực bằng không'),
-- ChuDe 27: Cac dinh luat bao toan
(27,4,10,'Vật rơi tự do từ h = 20 m (g = 10 m/s²). Tốc độ chạm đất (m/s) bằng bao nhiêu?','2','0',' ',' ','v = √(2gh) = √(2×10×20) = √400 = 20 m/s'),
-- ChuDe 28: Chat khi - Dinh luat chat khi
(28,4,10,'Nhiệt độ 0°C bằng bao nhiêu Kelvin? Điền 3 chữ số:','2','7','3',' ','T(K) = t(°C) + 273 = 0 + 273 = 273 K (điểm không tuyệt đối = −273°C)'),
-- ChuDe 29: Co so nhiet dong luc hoc
(29,4,10,'Nguyên lý I NĐLH: Q = ΔU + A. Cho Q = 500 J và A = 200 J. Nội năng ΔU (J) = ?','3','0','0',' ','ΔU = Q − A = 500 − 200 = 300 J'),
-- ChuDe 30: Chat long - Chat ran - Su chuyen the
(30,4,10,'Nước sôi ở 100°C ở áp suất chuẩn. Nhiệt độ đó bằng bao nhiêu Kelvin? Điền 3 chữ số:','3','7','3',' ','T = t + 273 = 100 + 273 = 373 K'),
-- ===================== HOA HOC 11 (ChuDe 31-38) =====================
-- ChuDe 31: Su dien li
(31,5,11,'Dung dịch HCl nồng độ 0.01 M ở 25°C có pH = bao nhiêu? Điền 1 chữ số:','2',' ',' ',' ','HCl → H⁺ + Cl⁻; [H⁺] = 0.01 = 10⁻² M → pH = 2'),
-- ChuDe 32: Nito va hop chat cua Nito
(32,5,11,'Phân tử khối của khí amoniac NH₃ là bao nhiêu g/mol? Điền 2 chữ số:','1','7',' ',' ','M(NH₃) = 14 + 3×1 = 17 g/mol'),
-- ChuDe 33: Photpho va hop chat cua Photpho
(33,5,11,'Trong axit photphoric H₃PO₄ có bao nhiêu nguyên tử Oxi? Điền số:','4',' ',' ',' ','H₃PO₄: 3H + 1P + 4O → số nguyên tử O = 4'),
-- ChuDe 34: Cacbon - Silic va hop chat
(34,5,11,'CO₂ + 2NaOH → Na₂CO₃ + H₂O. Tỉ lệ mol NaOH : CO₂ trong phản ứng này là?','2',' ',' ',' ','2 mol NaOH phản ứng với 1 mol CO₂ tạo muối trung hòa Na₂CO₃'),
-- ChuDe 35: Dai cuong hoa hoc huu co
(35,5,11,'Metan là ankan đơn giản nhất với công thức CH_. Điền số H còn thiếu:','4',' ',' ',' ','CₙH₂ₙ₊₂ với n=1 → CH₄; metan là chất khí không màu, không mùi'),
-- ChuDe 36: Hidrocacbon no (Ankan)
(36,5,11,'Propan (ankan có 3C) có công thức C₃H_. Điền số H còn thiếu (1 chữ số):','8',' ',' ',' ','CₙH₂ₙ₊₂ với n=3 → H = 2×3+2 = 8 → C₃H₈ (propan)'),
-- ChuDe 37: Hidrocacbon khong no (Anken - Ankadien - Ankin)
(37,5,11,'Etilen C₂H₄ có bao nhiêu liên kết đôi C=C trong một phân tử? Điền số:','1',' ',' ',' ','C₂H₄ là anken có 1 liên kết đôi C=C; phản ứng cộng là đặc trưng'),
-- ChuDe 38: Ancol - Phenol - Andehit - Axit cacboxylic
(38,5,11,'Ancol etylic C₂H₅OH có bao nhiêu nguyên tử Cacbon trong phân tử? Điền số:','2',' ',' ',' ','C₂H₅OH: có 2 nguyên tử C (ancol no, đơn chức, mạch hở)'),
-- ===================== SINH HOC 9 (ChuDe 39-46) =====================
-- ChuDe 39: Di truyen va bien di - Cac thi nghiem Menden
(39,6,9,'Lai hai cặp tính trạng độc lập theo Menđen, F₂ phân li 9:3:3:_. Điền số:','1',' ',' ',' ','9:3:3:1 — tỉ lệ kiểu hình F₂ khi hai cặp gen phân li độc lập'),
-- ChuDe 40: NST va di truyen lien ket
(40,6,9,'Mỗi tế bào sinh dưỡng của người bình thường có bao nhiêu cặp NST? Điền 2 chữ số:','2','3',' ',' ','2n = 46 NST = 23 cặp (22 cặp NST thường + 1 cặp NST giới tính)'),
-- ChuDe 41: ADN - Gen - Ma di truyen
(41,6,9,'Phân tử ADN có cấu trúc xoắn kép gồm bao nhiêu mạch đơn? Điền số:','2',' ',' ',' ','ADN = 2 mạch đơn song song ngược chiều, liên kết bổ sung A−T và G−X'),
-- ChuDe 42: Bien di - Dot bien gen va dot bien NST
(42,6,9,'ADN có bao nhiêu loại bazơ nitơ (nuclêôtit)? Điền số:','4',' ',' ',' ','4 loại bazơ: A (Adenin), T (Timin), G (Guanin), X (Xitozin)'),
-- ChuDe 43: Di truyen hoc nguoi
(43,6,9,'Hội chứng Down do thừa bao nhiêu NST số 21 so với người bình thường? Điền số:','1',' ',' ',' ','Người bình thường có 2 NST số 21; bệnh Down có 3 NST số 21 → thừa 1'),
-- ChuDe 44: Ung dung di truyen hoc vao chon giong
(44,6,9,'Ưu thế lai đạt cao nhất ở thế hệ ____. Điền ký hiệu (gồm 2 ký tự: F và số):','F','1',' ',' ','Ưu thế lai đỉnh cao ở F₁, sau đó giảm dần qua các thế hệ tự thụ phấn'),
-- ChuDe 45: Sinh vat va moi truong - Cac nhan to sinh thai
(45,6,9,'Khoảng nhân tố sinh thái mà sinh vật có thể tồn tại nhưng không thuận lợi gọi là khoảng "____". Điền 4 ký tự:','c','h','ị','u','Khoảng "chịu đựng" nằm ngoài khoảng thuận lợi, sinh vật sống được nhưng kém phát triển'),
-- ChuDe 46: He sinh thai - Can bang sinh hoc
(46,6,9,'Ngoài vi khuẩn, sinh vật phân giải xác chết còn có ____. Điền 3 ký tự:','n','ấ','m',' ','Vi khuẩn và nấm là hai nhóm sinh vật phân giải chính, trả chất vô cơ về môi trường'),
-- ===================== LICH SU 8 (ChuDe 47-54) =====================
-- ChuDe 47: Chau Au va nuoc My cuoi XVIII - dau XX
(47,7,8,'Cách mạng Pháp 1789 xảy ra vào thế kỷ thứ bao nhiêu? Điền 2 chữ số:','1','8',' ',' ','Năm 1789 thuộc thế kỷ XVIII — thời kỳ CNTB phát triển và các cuộc cách mạng tư sản'),
-- ChuDe 48: A - Phi - My Latinh cuoi XIX - dau XX
(48,7,8,'Phong trào Nghĩa Hòa Đoàn ở Trung Quốc chống đế quốc nổ ra năm ____. Điền 4 chữ số:','1','9','0','0','Nghĩa Hòa Đoàn (1900) — phong trào nông dân chống đế quốc xâm lược Trung Quốc'),
-- ChuDe 49: Chien tranh the gioi thu nhat (1914-1918)
(49,7,8,'Chiến tranh thế giới thứ nhất bùng nổ năm ____. Điền 4 chữ số:','1','9','1','4','CTTG I bắt đầu 28/7/1914 sau vụ ám sát Thái tử Áo-Hung Franz Ferdinand tại Sarajevo'),
-- ChuDe 50: CMT10 Nga 1917 va Lien Xo (1917-1941)
(50,7,8,'Cách mạng tháng Mười Nga thành công năm ____. Điền 4 chữ số:','1','9','1','7','Đêm 25/10/1917 (lịch cũ) = 7/11/1917 (lịch mới), Bolshevik giành chính quyền'),
-- ChuDe 51: Cac nuoc tu ban giua hai cuoc chien (1919-1939)
(51,7,8,'Cuộc đại khủng hoảng kinh tế thế giới bắt đầu năm ____. Điền 4 chữ số:','1','9','2','9','Khủng hoảng 1929-1933 bắt đầu từ Mỹ, lan rộng toàn cầu, đẩy các nước đến phát xít hóa'),
-- ChuDe 52: Chien tranh the gioi thu hai (1939-1945)
(52,7,8,'Chiến tranh thế giới thứ hai kết thúc hoàn toàn năm ____. Điền 4 chữ số:','1','9','4','5','Đức đầu hàng 8/5/1945; Nhật đầu hàng 2/9/1945 → CTTG II kết thúc hoàn toàn'),
-- ChuDe 53: Viet Nam tu 1858 den cuoi the ky XIX
(53,7,8,'Thực dân Pháp nổ súng tấn công Đà Nẵng, mở đầu xâm lược Việt Nam năm ____. (4 chữ số):','1','8','5','8','Ngày 1/9/1858, liên quân Pháp−Tây Ban Nha tấn công bán đảo Sơn Trà (Đà Nẵng)'),
-- ChuDe 54: Viet Nam dau the ky XX den 1918
(54,7,8,'Nguyễn Tất Thành rời bến Nhà Rồng ra đi tìm đường cứu nước năm ____. Điền 4 chữ số:','1','9','1','1','Ngày 5/6/1911, Người lên tàu Amiral Latouche-Tréville tại cảng Sài Gòn bắt đầu hành trình cứu nước');

-- =========================================================
-- Cau_hoi_trac_nghiem_dung_sai: 20 cau bo sung
-- Phan bo: Toan10(3), Van11(3), Anh12(2), Ly10(3), Hoa11(3), Sinh9(3), Su8(3)
-- =========================================================
INSERT INTO Cau_hoi_trac_nghiem_dung_sai
(ID_ChuDe, ID_MonHoc, ID_KhoiLop,
 NoiDungCauHoi_TracNghiemDungSai,
 NoiDungMenhDe1_TracNghiemDungSai,
 NoiDungMenhDe2_TracNghiemDungSai,
 NoiDungMenhDe3_TracNghiemDungSai,
 NoiDungMenhDe4_TracNghiemDungSai,
 DapAn_TracNghiem4PhuongAn,
 HuongDanGiaiMenhDe1_TracNghiemDungSai,
 HuongDanGiaiMenhDe2_TracNghiemDungSai,
 HuongDanGiaiMenhDe3_TracNghiemDungSai,
 HuongDanGiaiMenhDe4_TracNghiemDungSai)
VALUES
-- ===================== TOAN 10 =====================
-- ChuDe 6: Tich vo huong
(6,1,10,'Về tích vô hướng của hai vectơ:',
 'a⃗·b⃗ = |a⃗||b⃗|cos(α) trong đó α là góc giữa hai vectơ',
 'Nếu a⃗·b⃗ = 0 thì hai vectơ cùng phương với nhau',
 'Trong tam giác, định lý cosin: a² = b² + c² - 2bc·cosA',
 'Hai vectơ vuông góc khi và chỉ khi tích vô hướng bằng 0',
 'Đ,S,Đ,Đ',
 'Đúng: định nghĩa tích vô hướng','Sai: tích vô hướng = 0 ⟺ hai vectơ vuông góc, không phải cùng phương','Đúng: công thức định lý cosin trong tam giác','Đúng: điều kiện vuông góc của hai vectơ'),
-- ChuDe 7: Phuong phap toa do trong mat phang
(7,1,10,'Về đường thẳng trong hệ tọa độ Oxy:',
 'Phương trình tổng quát của đường thẳng có dạng ax + by + c = 0 (a² + b² > 0)',
 'Vectơ pháp tuyến của đường thẳng ax + by + c = 0 là n⃗ = (b; -a)',
 'Khoảng cách từ M(x₀;y₀) đến đường thẳng ax+by+c=0 là |ax₀+by₀+c|/√(a²+b²)',
 'Hai đường thẳng song song khi và chỉ khi chúng có cùng vectơ pháp tuyến',
 'Đ,S,Đ,S',
 'Đúng: dạng chuẩn của phương trình đường thẳng','Sai: vectơ pháp tuyến là n⃗ = (a; b), không phải (b; -a)','Đúng: công thức khoảng cách từ điểm đến đường thẳng','Sai: song song khi vectơ pháp tuyến song song nhưng đường thẳng không trùng nhau'),
-- ChuDe 8: Thong ke va xac suat
(8,1,10,'Về thống kê và xác suất cơ bản:',
 'Số trung bình cộng bằng tổng các giá trị chia cho số phần tử của mẫu',
 'Độ lệch chuẩn là bình phương của phương sai',
 'Xác suất của một biến cố luôn nằm trong đoạn [0; 1]',
 'Biến cố chắc chắn có xác suất bằng 1, biến cố không thể có xác suất bằng 0',
 'Đ,S,Đ,Đ',
 'Đúng: công thức tính trung bình cộng','Sai: phương sai = bình phương của độ lệch chuẩn, không phải ngược lại','Đúng: xác suất thuộc [0;1] là tính chất cơ bản','Đúng: P(Ω)=1 và P(∅)=0'),
-- ===================== NGU VAN 11 =====================
-- ChuDe 9: Tho chu Nom va tho chu Han trung dai
(9,2,11,'Về thơ chữ Nôm và thơ chữ Hán trung đại:',
 'Nguyễn Trãi có tập "Quốc âm thi tập" là tập thơ chữ Nôm sớm nhất còn lại đến nay',
 'Hồ Xuân Hương được mệnh danh là "Bà chúa thơ Đường"',
 'Thơ chữ Hán của Nguyễn Du gồm "Thanh Hiên thi tập" và "Nam trung tạp ngâm"',
 '"Truyện Lục Vân Tiên" của Nguyễn Đình Chiểu được viết bằng chữ Nôm',
 'Đ,S,Đ,Đ',
 'Đúng: "Quốc âm thi tập" là di sản thơ Nôm quý báu','Sai: bà được gọi là "Bà chúa thơ Nôm"','Đúng: Nguyễn Du để lại nhiều tập thơ chữ Hán','Đúng: tác phẩm viết bằng thơ lục bát chữ Nôm'),
-- ChuDe 11: Van xuoi hien thuc phe phan
(11,2,11,'Về tác phẩm "Chí Phèo" của Nam Cao:',
 'Chí Phèo là nhân vật nông dân bị tha hóa do xã hội thực dân phong kiến',
 'Bá Kiến đã giúp đỡ Chí Phèo trở thành người lương thiện',
 'Thị Nở là người đã thức tỉnh phần người còn lại trong Chí Phèo',
 'Tác phẩm phê phán xã hội đã đẩy người nông dân vào bước đường cùng',
 'Đ,S,Đ,Đ',
 'Đúng: bi kịch bị tha hóa của người nông dân','Sai: Bá Kiến chính là kẻ đẩy Chí Phèo vào tù và gây ra sự tha hóa','Đúng: tình yêu của Thị Nở khơi dậy khát vọng hoàn lương','Đúng: giá trị nhân đạo và hiện thực sâu sắc của tác phẩm'),
-- ChuDe 12: Tho moi (1932-1945)
(12,2,11,'Về phong trào Thơ mới (1932-1945):',
 'Thơ mới phá bỏ niêm luật thơ Đường, tự do hơn về hình thức và cảm xúc',
 'Xuân Diệu được Hoài Thanh gọi là "nhà thơ mới nhất trong các nhà Thơ mới"',
 'Hàn Mặc Tử có phong cách thơ độc đáo với những hình ảnh kỳ ảo, siêu thực',
 'Thơ mới là sản phẩm của văn hóa thực dân Pháp, không có giá trị văn học',
 'Đ,Đ,Đ,S',
 'Đúng: đặc trưng cốt lõi của Thơ mới','Đúng: đánh giá của nhà phê bình Hoài Thanh trong "Thi nhân Việt Nam"','Đúng: thơ Hàn Mặc Tử đầy hình ảnh tôn giáo và siêu thực','Sai: Thơ mới có giá trị văn học lớn, đánh dấu bước hiện đại hóa thơ ca Việt Nam'),
-- ===================== TIENG ANH 12 =====================
-- ChuDe 16: Unit 1 - Life Stories
(16,3,12,'Read the statements about vocabulary in Unit 1 "Life Stories":',
 '"Milestone" refers to an important event or stage in someone''s life',
 '"Reminisce" means to forget completely about past experiences',
 '"Memoir" is a written account of someone''s personal experiences and memories',
 'The past perfect tense is often used to describe events that happened before another past event',
 'Đ,S,Đ,Đ',
 'True: a milestone marks a significant achievement or turning point','False: reminisce means to think about past events with pleasure or nostalgia','True: memoirs are personal narrative accounts','True: e.g. "By the time she arrived, he had already left"'),
-- ChuDe 18: Unit 3 - The Green Movement
(18,3,12,'Read the statements about environmental issues:',
 'Renewable energy sources include solar, wind, and hydroelectric power',
 'Excess carbon dioxide (CO₂) in the atmosphere is beneficial for all ecosystems',
 'Recycling helps reduce waste sent to landfill and conserves natural resources',
 'Deforestation contributes to climate change by reducing carbon absorption',
 'Đ,S,Đ,Đ',
 'True: these are all forms of clean, renewable energy','False: excess CO₂ causes global warming and ocean acidification','True: recycling is a key strategy in waste management','True: forests act as carbon sinks; losing them increases CO₂ levels'),
-- ===================== VAT LY 10 =====================
-- ChuDe 27: Cac dinh luat bao toan
(27,4,10,'Về định luật bảo toàn năng lượng và cơ năng:',
 'Năng lượng không tự sinh ra cũng không tự mất đi, chỉ chuyển hóa từ dạng này sang dạng khác',
 'Trong con lắc đơn lý tưởng, động năng và thế năng không thể chuyển hóa qua lại',
 'Cơ năng được bảo toàn khi chỉ có lực thế (không có ma sát và lực cản)',
 'Công thức tính động năng: Eđ = ½mv²',
 'Đ,S,Đ,Đ',
 'Đúng: nguyên lý bảo toàn và chuyển hóa năng lượng','Sai: trong con lắc, động năng và thế năng liên tục chuyển hóa qua lại','Đúng: điều kiện bảo toàn cơ năng','Đúng: công thức động năng cơ học'),
-- ChuDe 28: Chat khi - Dinh luat chat khi
(28,4,10,'Về chất khí lý tưởng và các định luật chất khí:',
 'Định luật Boyle-Mariotte: ở nhiệt độ không đổi, áp suất và thể tích tỉ lệ nghịch (pV = const)',
 'Nhiệt độ tuyệt đối T(K) = Nhiệt độ Celsius t(°C) - 273',
 'Phương trình trạng thái khí lý tưởng: pV/T = const',
 'Ở áp suất không đổi, thể tích khí tỉ lệ thuận với nhiệt độ tuyệt đối (Định luật Charles)',
 'Đ,S,Đ,Đ',
 'Đúng: đẳng nhiệt pV = const','Sai: T(K) = t(°C) + 273, không phải trừ 273','Đúng: phương trình Clapeyron cho khí lý tưởng','Đúng: đẳng áp V/T = const'),
-- ChuDe 30: Chat long - Chat ran - Su chuyen the
(30,4,10,'Về sự chuyển thể và nhiệt học:',
 'Sự bay hơi là quá trình chuyển từ thể lỏng sang thể khí diễn ra ở bề mặt chất lỏng',
 'Nhiệt nóng chảy của một chất là lượng nhiệt cần để làm chất đó sôi',
 'Nhiệt độ nóng chảy của một chất tinh khiết là xác định ở áp suất chuẩn',
 'Nhiệt độ sôi của chất lỏng phụ thuộc vào áp suất tác dụng lên bề mặt chất lỏng',
 'Đ,S,Đ,Đ',
 'Đúng: bay hơi khác sôi ở chỗ chỉ xảy ra ở bề mặt','Sai: nhiệt nóng chảy là nhiệt dùng để chuyển từ rắn sang lỏng, không phải để sôi','Đúng: mỗi chất có nhiệt độ nóng chảy đặc trưng','Đúng: vì vậy nước sôi ở 100°C ở áp suất chuẩn nhưng sôi thấp hơn trên núi cao'),
-- ===================== HOA HOC 11 =====================
-- ChuDe 31: Su dien li
(31,5,11,'Về sự điện li và dung dịch axit-bazơ:',
 'Chất điện li mạnh phân li hoàn toàn trong dung dịch (ví dụ: HCl, NaOH, H₂SO₄)',
 'Axit axetic (CH₃COOH) là chất điện li mạnh, phân li hoàn toàn trong nước',
 'Môi trường axit có pH < 7 và môi trường bazơ có pH > 7 (ở 25°C)',
 'Phản ứng trung hòa giữa axit mạnh và bazơ mạnh tạo ra muối và nước',
 'Đ,S,Đ,Đ',
 'Đúng: HCl → H⁺ + Cl⁻ (100%)','Sai: CH₃COOH là axit yếu, điện li một phần trong nước','Đúng: pH < 7 → axit; pH > 7 → bazơ; pH = 7 → trung tính','Đúng: HCl + NaOH → NaCl + H₂O'),
-- ChuDe 33: Photpho va hop chat
(33,5,11,'Về Photpho và axit photphoric (H₃PO₄):',
 'Photpho trắng độc hơn photpho đỏ và có thể tự bốc cháy trong không khí',
 'Axit photphoric (H₃PO₄) là axit mạnh, phân li hoàn toàn trong dung dịch loãng',
 'Phân lân cung cấp nguyên tố P cần thiết cho sự phát triển của cây trồng',
 'H₃PO₄ tác dụng với NaOH có thể tạo ra ba loại muối tùy tỉ lệ mol',
 'Đ,S,Đ,Đ',
 'Đúng: P trắng bảo quản trong nước vì dễ bốc cháy','Sai: H₃PO₄ là axit trung bình (ba nấc, Ka nhỏ)','Đúng: P là nguyên tố dinh dưỡng đa lượng cho cây','Đúng: tạo NaH₂PO₄, Na₂HPO₄ hoặc Na₃PO₄'),
-- ChuDe 36: Hidrocacbon no (Ankan)
(36,5,11,'Về hiđrocacbon no (ankan):',
 'Ankan có công thức phân tử tổng quát là CₙH₂ₙ₊₂ (n ≥ 1)',
 'Metan (CH₄) là chất lỏng không màu, không mùi ở điều kiện thường',
 'Phản ứng đặc trưng của ankan là phản ứng thế với halogen khi có ánh sáng',
 'Ankan không tan trong nước nhưng tan tốt trong dung môi hữu cơ không phân cực',
 'Đ,S,Đ,Đ',
 'Đúng: công thức chung của dãy đồng đẳng ankan','Sai: metan là chất khí không màu, không mùi ở điều kiện thường','Đúng: halogen hóa là phản ứng đặc trưng của ankan','Đúng: ankan kị nước nhưng tan trong xăng, benzen,...'),
-- ===================== SINH HOC 9 =====================
-- ChuDe 39: Di truyen Menden
(39,6,9,'Về các quy luật di truyền của Menđen:',
 'Quy luật phân li: trong quá trình tạo giao tử, mỗi nhân tố di truyền phân li về một giao tử',
 'Khi lai hai cặp tính trạng thuần chủng khác nhau, F₂ phân li theo tỉ lệ 3:1',
 'Alen trội át chế alen lặn trong kiểu gen dị hợp (Aa biểu hiện kiểu hình trội)',
 'Lai phân tích là lai con lai F₁ với thể đồng hợp lặn (aa) để xác định kiểu gen F₁',
 'Đ,S,Đ,Đ',
 'Đúng: nền tảng của quy luật phân li Menđen','Sai: F₂ phân li 9:3:3:1 khi hai cặp gen phân li độc lập; 3:1 chỉ đúng cho một cặp tính trạng','Đúng: trội hoàn toàn - Aa có kiểu hình giống AA','Đúng: để phân biệt Aa với AA'),
-- ChuDe 45: Moi truong va nhan to sinh thai - quan the
(45,6,9,'Về quần thể sinh vật và đặc trưng quần thể:',
 'Quần thể là tập hợp các cá thể cùng loài, cùng sống trong một khu vực và thời gian nhất định',
 'Mật độ quần thể không ảnh hưởng đến tốc độ sinh trưởng của quần thể',
 'Tỉ lệ giới tính và cấu trúc tuổi là những đặc trưng cơ bản của quần thể',
 'Khi điều kiện môi trường thuận lợi, quần thể có xu hướng tăng số lượng cá thể',
 'Đ,S,Đ,Đ',
 'Đúng: định nghĩa quần thể sinh vật','Sai: mật độ cao → cạnh tranh → ảnh hưởng lớn đến sinh trưởng','Đúng: hai đặc trưng quan trọng phản ánh trạng thái quần thể','Đúng: quần thể phát triển trong môi trường thuận lợi'),
-- ChuDe 46: He sinh thai - Chu trinh vat chat
(46,6,9,'Về chu trình vật chất trong hệ sinh thái:',
 'Carbon đi vào chu trình sinh học thông qua quang hợp của thực vật xanh',
 'Cây xanh có thể hấp thụ trực tiếp N₂ từ không khí để tổng hợp protein',
 'Vi sinh vật phân giải đóng vai trò tái tạo chất vô cơ từ xác sinh vật',
 'Ô nhiễm môi trường làm rối loạn chu trình vật chất và gây mất cân bằng hệ sinh thái',
 'Đ,S,Đ,Đ',
 'Đúng: quang hợp cố định CO₂ vào chất hữu cơ','Sai: cây hấp thụ N ở dạng NO₃⁻ hoặc NH₄⁺; chỉ vi khuẩn cố định đạm mới dùng được N₂','Đúng: vi khuẩn và nấm phân giải xác chết thành chất vô cơ','Đúng: phá vỡ cân bằng sinh thái tự nhiên'),
-- ===================== LICH SU 8 =====================
-- ChuDe 49: Chien tranh the gioi thu nhat (1914-1918)
(49,7,8,'Về Chiến tranh thế giới thứ nhất (1914-1918):',
 'Chiến tranh bùng nổ năm 1914 sau vụ ám sát Thái tử Áo-Hung Franz Ferdinand tại Sarajevo',
 'Đức, Pháp và Anh đều thuộc phe Liên minh (Đức - Áo - Hung - Italia)',
 'Chiến tranh kết thúc năm 1918 với sự thất bại của phe Liên minh',
 'Hòa ước Versailles (1919) buộc Đức bồi thường chiến phí và cắt nhượng lãnh thổ',
 'Đ,S,Đ,Đ',
 'Đúng: ngòi nổ dẫn đến chiến tranh thế giới','Sai: Pháp và Anh thuộc phe Hiệp ước (Entente); phe Liên minh gồm Đức - Áo - Hung','Đúng: phe Liên minh thất bại năm 1918','Đúng: Hòa ước Versailles giáng đòn nặng vào nước Đức'),
-- ChuDe 50: Cach mang thang Muoi Nga 1917 va Lien Xo
(50,7,8,'Về Cách mạng tháng Mười Nga năm 1917:',
 'Đảng Bolshevik do Lênin lãnh đạo đã tiến hành Cách mạng tháng Mười',
 'Cách mạng tháng Hai 1917 đã lật đổ chính phủ tư sản lâm thời và lập nên nhà nước Xô viết',
 'Cách mạng tháng Mười mở ra kỷ nguyên cách mạng xã hội chủ nghĩa trên thế giới',
 'Sau cách mạng, nhà nước Liên Xô do Đảng Cộng sản Bolshevik lãnh đạo được thành lập',
 'Đ,S,Đ,Đ',
 'Đúng: Lênin lãnh đạo cuộc tổng khởi nghĩa đêm 24-25/10/1917','Sai: Cách mạng tháng Hai chỉ lật đổ Nga hoàng; Cách mạng tháng Mười mới lật đổ chính phủ tư sản lâm thời','Đúng: ảnh hưởng của Cách mạng Nga tới phong trào cách mạng toàn thế giới','Đúng: Lênin trở thành lãnh tụ nhà nước Xô viết đầu tiên'),
-- ChuDe 52: Chien tranh the gioi thu hai (1939-1945)
(52,7,8,'Về Chiến tranh thế giới thứ hai (1939-1945):',
 'Chiến tranh bắt đầu ngày 1/9/1939 khi phát xít Đức tấn công Ba Lan',
 'Liên Xô là đồng minh của phát xít Đức trong suốt Chiến tranh thế giới thứ hai',
 'Sự kiện Nhật tấn công Trân Châu Cảng (12/1941) kéo Mỹ chính thức tham chiến',
 'Chiến tranh kết thúc hoàn toàn vào tháng 9/1945 sau khi Nhật Bản đầu hàng',
 'Đ,S,Đ,Đ',
 'Đúng: ngày 1/9/1939 - ngày mở đầu CTTG II','Sai: Liên Xô ban đầu ký Hiệp ước Molotov-Ribbentrop nhưng sau đó chống phát xít từ 1941','Đúng: Nhật tấn công Trân Châu Cảng ngày 7/12/1941','Đúng: Nhật đầu hàng ngày 2/9/1945 kết thúc hoàn toàn CTTG II');

-- =========================================================
-- 3) Du lieu mau vận hành (De_Thi, De_Thi_Chi_Tiet, Ky_thi,
--    Thong_bao, Diem_danh, Don_xin_nghi)
-- =========================================================

-- ---------------------------------------------------------
-- De_Thi: 2 de mau
-- ID_NguoiTao: GV2=Toan10, GV7=Sinh9
-- ---------------------------------------------------------
INSERT INTO De_Thi (TenDeThi, ID_NguoiTao, ID_MaMon, ID_MaKhoi, MoTa)
VALUES
('Đề Kiểm Tra Toán 10 - Học Kỳ 1', 2, 1, 10, 'Đề kiểm tra giữa kỳ 1, gồm mệnh đề, tập hợp và hàm số'),
('Đề Kiểm Tra Sinh Học 9 - Học Kỳ 1', 7, 6, 9,  'Đề kiểm tra giữa kỳ 1, chủ đề di truyền và biến dị');

-- ---------------------------------------------------------
-- De_Thi_Chi_Tiet: gan cau hoi vao de
-- De 1 (Toan10, ID_MaDeThi=1): 4PA ID 1-3, DS ID 1-2, Ngan ID 1
-- De 2 (Sinh9,  ID_MaDeThi=2): 4PA ID 115-117, DS ID 77-78, Ngan ID 39
-- ID_NguoiTao / ID_MaMon / ID_MaKhoi khop voi De_Thi tuong ung
-- ---------------------------------------------------------
INSERT INTO De_Thi_Chi_Tiet
    (ID_MaDeThi, ID_NguoiTao, ID_MaMon, ID_MaKhoi,
     ID_TracNghiem4PhuongAn, ID_TracNghiemDungSai, ID_TracNghiemTraLoiNgan)
VALUES
-- De 1 - Toan 10
(1, 2, 1, 10,  1,    NULL, NULL),
(1, 2, 1, 10,  2,    NULL, NULL),
(1, 2, 1, 10,  3,    NULL, NULL),
(1, 2, 1, 10,  NULL, 1,    NULL),
(1, 2, 1, 10,  NULL, 2,    NULL),
(1, 2, 1, 10,  NULL, NULL, 1),
-- De 2 - Sinh 9
(2, 7, 6, 9,   115,  NULL, NULL),
(2, 7, 6, 9,   116,  NULL, NULL),
(2, 7, 6, 9,   117,  NULL, NULL),
(2, 7, 6, 9,   NULL, 77,   NULL),
(2, 7, 6, 9,   NULL, 78,   NULL),
(2, 7, 6, 9,   NULL, NULL, 39);

-- ---------------------------------------------------------
-- Ky_thi: 2 ky thi mau gan vao lop va de tuong ung
-- ID_LopHoc: 1=10A1 (Toan), 6=9A1 (Sinh)
-- ID_ChuDe: 1=Menh de va tap hop (Toan10), 39=Di truyen Menden (Sinh9)
-- ThoiGianLamBai: phut
-- PhanBoDiem: tong 10 diem
-- ---------------------------------------------------------
INSERT INTO Ky_thi
    (ID_KhoiLop, ID_MonHoc, ID_ChuDe, ID_LopHoc, Ten_KyThi, MoTa_KyThi,
     ThoiGianLamBai_KyThi,
     PhanBoDiemTracNghiem4PhuongAn_KyThi,
     PhanBoDiemTracNghiemDungSai_KyThi,
     PhanBoDiemTracNghiemTraLoiNgan_KyThi,
     SoCauHoiTracNghiem4PhuongAn_KyThi,
     SoCauHoiTracNghiemDungSai_KyThi,
     SoCauHoiTracNghiemTraLoiNgan_KyThi,
     ID_MaDeThi, ThoiGianBatDau_KyThi, ThoiGianKetThuc_KyThi)
VALUES
(10, 1, 1,  1, 'Kỳ Thi Giữa Kỳ 1 - Toán 10A1', 'Kiểm tra giữa học kỳ 1 môn Toán lớp 10A1',
 45,  4.00, 3.00, 3.00,  3, 2, 1,
 1, '2025-11-15 07:30:00', '2025-11-15 08:15:00'),
(9,  6, 39, 6, 'Kỳ Thi Giữa Kỳ 1 - Sinh 9A1',  'Kiểm tra giữa học kỳ 1 môn Sinh học lớp 9A1',
 45,  4.00, 3.00, 3.00,  3, 2, 1,
 2, '2025-11-16 07:30:00', '2025-11-16 08:15:00');

-- ---------------------------------------------------------
-- Thong_bao: 3 thong bao mau
-- ---------------------------------------------------------
INSERT INTO Thong_bao (ID_User, ID_KhoiLop, ID_MonHoc, NoiDung_ThongBao)
VALUES
(1, NULL, NULL, 'Chào mừng năm học 2024-2025! Hệ thống thi trực tuyến đã sẵn sàng phục vụ.'),
(2, 10,   1,    'Các em học sinh lớp Toán 10 lưu ý: kỳ thi giữa kỳ sẽ diễn ra ngày 15/11/2025, ôn tập chủ đề mệnh đề và tập hợp.'),
(7, 9,    6,    'Học sinh lớp Sinh 9 chuẩn bị ôn tập phần di truyền Menđen trước kỳ thi ngày 16/11/2025.');

-- ---------------------------------------------------------
-- Diem_danh: 2 buoi hoc mau
-- ID_LopHoc: 1=10A1, 6=9A1
-- ---------------------------------------------------------
INSERT INTO Diem_danh
    (ID_LopHoc, NgayHoc_DiemDanh,
     ThoiGianBatDau_DiemDanh, ThoiGianKetThuc_DiemDanh,
     TrangThaiBuoiHoc_DiemDanh)
VALUES
(1, '2025-10-20', '2025-10-20 07:30:00', '2025-10-20 09:00:00', 'completed'),
(6, '2025-10-21', '2025-10-21 07:30:00', '2025-10-21 09:00:00', 'completed');

-- ---------------------------------------------------------
-- Don_xin_nghi: 1 don mau
-- ID_User=9 (hoc sinh dau tien lop 10A1), ID_DiemDanh=1
-- ---------------------------------------------------------
INSERT INTO Don_xin_nghi
    (ID_LopHoc, ID_User, ID_DiemDanh, NoiDung_DonXinNghi, TrangThai_DonXinNghi)
VALUES
(1, 9, 1, 'Em bị ốm và không thể đến trường vào buổi học ngày 20/10/2025. Kính mong thầy/cô xem xét và chấp thuận đơn xin nghỉ của em.', 'approved');
