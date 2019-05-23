# 扩展更新说明

## 扩展说明

LinCms TP 的反射验证器核心类，含参数中间件封装，路由参数验证中间件，方法注释参数提取器，基于`wangyu/reflex-core`扩展

## 2019-5-17 10点 更新 master 分支

### 更新内容

- 优化异常类输出
- 完善在linux环境的差异
- 修复在linux环境抛出的`致命错误 Class application//controller/ not found`的错误异常
- 新增更新日志文件


## 2019-5-17 11点 更新 master 分支

### 更新内容

- 修复在linux环境抛出的`致命错误 Class application//controller/ not found`的错误异常


## 2019-5-17 14点40 更新 master 分支

### 更新内容

- 优化`@validate`关键字定义的验证器类输出格式
- 新增`@validate`关键字定义的验证器类，全命名空间定义
  >  例如： **`@validate('\app\api\validate\user\LoginForm')`**
- 更新日志文件


## 2019-5-17 14点55 更新 master 分支

### 更新内容

- 优化错误输出

## 2019-5-23 23:09 更新 master 分支

### 更新内容

- 新增`支持注释验证器分组模式`
- 优化注释验证器传参模式
- 优化代码