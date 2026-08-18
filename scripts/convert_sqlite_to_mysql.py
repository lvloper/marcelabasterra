#!/usr/bin/env python3
import re
from pathlib import Path

repo = Path(__file__).resolve().parents[1]
input_file = repo / 'db-dump.sql'
output_file = repo / 'db-dump-mysql.sql'
text = input_file.read_text(encoding='utf-8')

# Normalize line endings
text = text.replace('\r\n', '\n')

# Prepare unnamed table counter
unnamed_count = 0

# 1) Replace CREATE TABLE "name" with CREATE TABLE `name`
def replace_create(match):
    global unnamed_count
    name = match.group(1)
    if name == '':
        unnamed_count += 1
        name = f'unnamed_table_{unnamed_count}'
    return f'CREATE TABLE `{name}`'

text = re.sub(r'CREATE TABLE "([^"]*)"', replace_create, text)

# 2) Replace INSERT INTO "name" with INSERT INTO `name`
text = re.sub(r'INSERT INTO "([^"]+)"', r'INSERT INTO `\1`', text)

# 3) Convert identifier occurrences in column lists: "col" -> `col`
# But avoid replacing inside string literals. We'll do cautious replacements inside CREATE TABLE blocks and elsewhere for quoted identifiers before space or comma or parenthesis.

def replace_quoted_identifiers_in_create(match):
    block = match.group(0)
    # replace "identifier" with `identifier`
    block = re.sub(r'"([A-Za-z0-9_]+)"', r'`\1`', block)
    return block

text = re.sub(r'CREATE TABLE `[^`]+`\s*\([^;]+?\);', replace_quoted_identifiers_in_create, text, flags=re.S)

# Also replace any remaining "identifier" occurrences that look like column refs (heuristic)
text = re.sub(r'"([A-Za-z0-9_]+)"', r'`\1`', text)

# 4) Process CREATE TABLE blocks to adjust column types and add ENGINE/CHARSET
create_blocks = re.findall(r'(CREATE TABLE `[^`]+`\s*\([^;]+?\);)', text, flags=re.S)
for block in create_blocks:
    original = block
    body = block
    # Replace common SQLite type patterns
    body = re.sub(r'\bINTEGER\b\s+PRIMARY\s+KEY\s+AUTOINCREMENT\s+NOT\s+NULL', 'int NOT NULL AUTO_INCREMENT PRIMARY KEY', body, flags=re.I)
    body = re.sub(r'\bINTEGER\b\s+PRIMARY\s+KEY\s+AUTOINCREMENT', 'int NOT NULL AUTO_INCREMENT PRIMARY KEY', body, flags=re.I)
    body = re.sub(r'\bINTEGER\b', 'int', body, flags=re.I)
    body = re.sub(r'\bTINYINT\(1\)\b\s+NOT\s+NULL\s+DEFAULT\s+\'0\'', "tinyint(1) NOT NULL DEFAULT 0", body, flags=re.I)
    body = re.sub(r"DEFAULT\s+'(\d+)'", r'DEFAULT \1', body)
    # Ensure NOT NULL/NULL keywords are uppercase
    body = re.sub(r'\bnot\s+null\b', 'NOT NULL', body, flags=re.I)
    body = re.sub(r'\bnull\b', 'NULL', body, flags=re.I)
    # Remove SQLite specific keywords
    body = re.sub(r'\bAUTOINCREMENT\b', 'AUTO_INCREMENT', body, flags=re.I)
    # Add ENGINE and CHARSET at end of CREATE TABLE
    if not re.search(r'ENGINE\s*=\s*', body, flags=re.I):
        body = body.rstrip('\n')
        body = re.sub(r'\);\s*$', ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n', body, flags=re.S)
    # Add DROP TABLE IF EXISTS before create
    table_name_match = re.search(r'CREATE TABLE `([^`]+)`', body)
    if table_name_match:
        tbl = table_name_match.group(1)
        drop = f'DROP TABLE IF EXISTS `{tbl}`;\n'
        body = drop + body
    # Replace in text
    text = text.replace(original, body)

# 5) Wrap with foreign key checks off/on
header = 'SET FOREIGN_KEY_CHECKS=0;\n\n'
footer = '\nSET FOREIGN_KEY_CHECKS=1;\n'
output = header + text.strip() + footer

output_file.write_text(output, encoding='utf-8')
print('Wrote', output_file)
