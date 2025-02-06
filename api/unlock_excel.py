import msoffcrypto
import sys

def unlock_xlsx(input_file, output_file, password):
    with open(input_file, "rb") as f:
        office_file = msoffcrypto.OfficeFile(f)
        office_file.load_key(password=password)  # 비밀번호 입력

        with open(output_file, "wb") as fout:
            office_file.decrypt(fout)  # 복호화 파일 저장

if __name__ == "__main__":
    if len(sys.argv) != 4:
        print("Usage: python excel.py <input.xlsx> <output.xlsx> <password>")
        sys.exit(1)

    unlock_xlsx(sys.argv[1], sys.argv[2], sys.argv[3])


# def unlock_xlsx(input_file, output_file, password):
#     with open(input_file, "rb") as f:
#         office_file = msoffcrypto.OfficeFile(f)
#         office_file.load_key(password=password)  # 비밀번호 입력

#         with open(output_file, "wb") as fout:
#             office_file.decrypt(fout)  # 암호 해제된 파일 저장

#     print(f"✅ 암호 해제 완료: {output_file}")

# # 사용 예시
# unlock_xlsx("test.xlsx", "unlock.xlsx", "4232")